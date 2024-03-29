<?php
/**
 *    SimpleVCARD php class v0.5.1
 *    vCalender Reader, Supports v2.0, v3.0, v4.0
 *
 * Copyright (c) 2021 SimpleVCARD
 *
 * @category   SimpleVCARD
 * @package    SimpleVCARD
 * @copyright  Copyright (c) 2021 SimpleVCARD (https://github.com/joetag47/simpleVCARD/)
 * @license    MIT
 * @version    0.5, 2021-04-03
 */


/**
 * 
 Examples
*
* //example 1
*if ( $vcard = SimpleVCARD::retrieve("contacts.vcf") ) {
*    print_r( $vcard->records );
* } else {
*    echo SimpleVCARD::parseError();
* }
*
* // example 2
* $data =  "BEGIN:VCARD
*          VERSION:2.1
*          FN:John Doe
*          TEL;CELL:+17030000000
*          EMAIL;HOME:john.doe@email.com
*          ADR;HOME:;;woodbug ;;;;
*          END:VCARD";
*
*if ( $vcard = SimpleVCARD::retrieve($data,"",true) ) {
*    echo "<pre>";
*    print_r( $vcard->records );
*    echo "</pre>";
* } else {
*    echo SimpleVCARD::parseError();
* }
*
* // example 3
* // check readme to find all supported prefixes
*$format =  "fn,ct,em";
*
*if ( $vcard = SimpleVCARD::retrieve("contact.vcf",$format) ) {
*    echo "<pre>";
*    print_r( $vcard->records );
*    echo "</pre>";
* } else {
*    echo SimpleVCARD::parseError();
* }
*
* // Example 4
* // check readme to find all supported prefixes
*$format =  "fn,ct,em,ph,ad";
*
* // debug flag
*$debug = true;
*
* // is data flag
*$data = false;
*
*if (isset($_FILES['file'])) {
*  
*  // file 
*  $vcftmp = $_FILES['file']['tmp_name'];
*  
*  if ( $vcard = new SimpleVCARD($vcftmp, $data, $format, $debug) ) {
*    echo "<pre>";
*    print_r( $vcard->records() );
*    echo "</pre>";
*  } else {
*      echo SimpleVCARD::parseError();
*  }
*}
*
* // Example 5 
* // render data in JSON format
*$vcard = SimpleVCARD::retrieve("contacts.vcf") );
*echo ( $vcard->toJSON() );
*
* // Example 6
* // render data in HTML format - simple
*$vcard = SimpleVCARD::retrieve("contacts.vcf") );
*
* // echo table
*echo ( $vcard->toHTML() );
*
* // Example 7
* // render data in HTML format - extra
*$vcard = SimpleVCARD::retrieve("contacts.vcf", "fn,ct,em") );
*
* // echo table. Id and class(es) should not contain # or . use spaces to seperate different class names
*echo ( $vcard->toHTML("id", "class1 or class2") );
*
*/

/**
 * 
 logs version 0.5

 * Library Specifications : 
 * vCard Reader extracts only fullname | contact | email | photo | address | organization | title | url | notes | birthday . 
 * Default Prefix : 
	-  fn => fullname,
	-  ct => contact,
	-  em => email,
	-  ph => photo,
	-  ad => address,
	-  org => organization,
	-  ti => title,
	-  ur => url,
	-  no => notes,
	-  bd => birthday


 */
class SimpleVCARD 
{
	// properties
	private $source;
	private $specs;
	// define prefix
	private $spec_defs = ['fn','ct','em','ph','ad','org','ti','ur','no','bd'];
	// defined with keys and values
	private $spec_defined = ['fn'=>'fullname','ct'=>'contact','em'=>'email','ph'=>'photo','ad'=>'address','org'=>'organization','ti'=>'title','ur'=>'url','no'=>'notes','bd'=>'birthday'];
	private $user_spec;
	private $contact_info_template;
	private $file;
	private $error = false;
	private $debug;
	private $person;
	public $records;

	function __construct($filename, $specs = "", $isdata = false, $debug = false)
	{
		# code...
		// set parameters
		$this->user_spec = '';
		$this->person = array();
		$this->records = array();
		$this->debug   = $debug;
		$this->source = array(
			'filename' => '',
			'size'     => 0,
			'records'  => array()
		);

		if (strlen($specs) > 0){

			// 
			$this->user_spec = $specs;

			// default specs
			$this->specs = array();

			if (strpos($specs, ",") == false){
				// 
				$this->error( 'Specification String is invalid, must look like e.g fn,em,cn etc!. ' . $filename );

				return false;
			}

			// 
			$spec_arr = explode(",", $specs);

			// 
			foreach ($spec_arr as $key) {
				// validate 
				if (in_array($key, $this->spec_defs)){
					// set def
					$this->specs[$this->spec_defined[$key]] = ""; 

				} else {
					// 
					$this->error( 'Specification Prefix is invalid, must look like e.g fn or em or cn etc, see docs for all valid Prefixes!. ' . $specs );

					return false;
				}
			}

			// var_dump($this->specs);
			
		} else {
			// default specs
			$this->specs = array(
									'fullname'=>'',
									'contact'=>'',
									'email'=>'',
									'photo'=>'',
									'address'=>'',
									'organization'=>'',
									'title'=>'',
									'url'=>'',
									'notes'=>'',
									'birthday'=>''
								);
			
		}

		$this->_read($filename, $isdata);
	}

	 
	private function _read($filename, $isdata){

		if ($isdata){

			// set source information
			$this->source["filename"] = $filename;
			$this->source["size"] = strlen($filename);

			if (!is_string($filename)){

				$this->error( 'Data is invalid, not a string' . $filename );

				return false;
			}

			else if (substr($filename, 0,11) !== "BEGIN:VCARD"){
				
				$this->error( 'Invalid String, not vcard' . $filename );

				return false;
			}

			// set 
			$_fl = $filename;

		} else {


			// stream type
			$mime = str_replace("x-", "", mime_content_type($filename));

			// check file validity
			if ( ! is_readable( $filename ) ) {
				$this->error( 'File not found ' . $filename );

				return false;
			}

			// media check type
			else if ($mime !== "text/vcard"){
				$this->error( 'Invalid File, not a vcard! file type is : ' . explode("/", $mime)[1]);

				return false;
			}

			// set source information
			$this->source["filename"] = $filename;
			$this->source["size"] = filesize($filename);

			// set 
			$_fl = file_get_contents($filename);
		}
		// 
		$_splits = explode("END:VCARD", $_fl);

		// 
		foreach ($_splits as $keys => $_split) {
			// validate
			if (strlen(trim($_split)) > 0) {
				// ex into fields
				$fields = explode("\n", $_split); 

				// template for specs
				$this->person = $this->specs;
				// set
				$this->person["contact"] = array();

				// cehck each field
				for ($i=0; $i < count($fields); $i++) { 

							
					if (strpos($fields[$i], "FN") !== false ){
								
						$fn = explode(":", $fields[$i]);

						if (!isset($fn[1])){
							$fn[1] = "";
						}
						// 
						$this->person["fullname"] = str_replace(["\n","\r","\n\r","\0"], "", $fn[1]);
					}

					if (strpos($fields[$i], "TEL") !== false ){
						$cn = explode(":", $fields[$i]);

						if (!isset($cn[1])){
							$cn[1] = "";
						}
						
						// check duplication of contacts
						if (!in_array(trim($cn[1]), $this->person["contact"])){
							//
							array_push($this->person["contact"], str_replace(["-","\n","\r","\n\r","\0"], "", trim($cn[1])));
						}
						
					}

					if (strpos($fields[$i], "EMAIL") !== false ){
						// 
						$em = explode(":", $fields[$i]);

						if (!isset($em[1])){
							$em[1] = "";
						}
						// 
						$this->person["email"] = str_replace(["\n","\r","\n\r","\0"], "", $em[1]);

					}

					if (strpos($fields[$i], "PHOTO") !== false ){
						$ph = explode(":", $fields[$i]);

						if (!isset($ph[1])){
							$ph[1] = "";
						}
						// 
						$this->person["photo"] = $ph[1];
					}

					if (strpos($fields[$i], "ADR") !== false ){
						$ad = explode(":", $fields[$i]);

						if (!isset($ad[1])){
							$ad[1] = "";
						}
						// 
						$this->person["address"] = str_replace(";", "", $ad[1]);
					}

					if (strpos($fields[$i], "ORG") !== false ){
						$or = explode(":", $fields[$i]);

						if (!isset($or[1])){
							$or[1] = "";
						}
						// 
						$this->person["organization"] = str_replace(";", "", $or[1]);
					}

					if (strpos($fields[$i], "TITLE") !== false ){
						$ti = explode(":", $fields[$i]);

						if (!isset($ti[1])){
							$ti[1] = "";
						}
						// 
						$this->person["title"] = str_replace(";", "", $ti[1]);
					}

					if (strpos($fields[$i], "URL") !== false ){
						$ur = explode(":", $fields[$i]);

						if (!isset($ur[1])){
							$ur[1] = "";
						}
						// 
						$this->person["url"] = str_replace(";", "", $ur[1]);
					}

					if (strpos($fields[$i], "NOTE") !== false ){
						$no = explode(":", $fields[$i]);

						if (!isset($no[1])){
							$no[1] = "";
						}
						// 
						$this->person["notes"] = str_replace(";", "", $no[1]);
					}

					if (strpos($fields[$i], "BDAY") !== false ){
						$bd = explode(":", $fields[$i]);

						if (!isset($bd[1])){
							$bd[1] = "";
						}
						// 
						$this->person["birthday"] = $bd[1];
					}

				}

				if (strlen($this->user_spec) > 0 ){
					// 
					$specs = explode(",", $this->user_spec);

					$specs_diff = array_diff( $this->spec_defs, $specs);

					// var_dump($specs_diff);

					foreach ($specs_diff as $key) {
						//
						if (!in_array($key, $specs)){
						// 
							unset($this->person[$this->spec_defined[$key]]);
						}
					}
	
				} 

				 // 
				array_push($this->records, $this->person);
				
			}

		}

		$this->source["records"] = $this->records;

	}
	
	// basic usage
	public static function retrieve($filename, $specs = "", $isdata = false, $debug = false){
		// 
		$vcard =  new self($filename,$specs, $isdata,$debug);

		if ($vcard->success()){
			// 
			return $vcard;
		} 

		self :: parseError( $vcard->error() );

		return false;
	}
	
	public function records(){
		return $this->source["records"];
	}
	
	// to json
	public function toJson(){
		return json_encode($this->source["records"]);
	}

	// to html
	public function toHTML($id = "",$class  = ""){
		// settings
		$t = "<table id='".$id."' class='".$class."'>";
		$th = "";
		$td = "";
		$tr = "";
		$c = 1;

		foreach ($this->specs as $key => $value) {
			//
			$th .= "<th>".$key."</th>";
			
		}

		$t .= "<thead style='text-align:left'><tr><th>No</th>".$th."</tr></thead><tbody>";

		foreach ($this->source["records"] as $r => $data) {
			//
			$td = "";

			foreach ($this->specs as $k => $value) {
				

				if ($k == "contact"){
					
					$ct = implode(", ", $data[$k]);
					//
					$td .= "<td>".$ct."</td>";

				} else {
					//
					$td .= "<td>".$data[$k]."</td>";
				}
			}

			$t .= "<tr><td>".$c++."</td>".$td."</tr>"."\r\n";
		}

		$t .= "<tbody></table>";

		return $t;
	}

	private function success(){

		return ! $this->error;

	}

	public static function parseError($error = false){
		$def = false;
		return  $error ? $def = $error : $def;
	}
	
	// 
	private function error($error = false){
		if ($error){
			$this->error = $error;
			if ($this->debug){
				trigger_error(__CLASS__ .": ".$error, E_USER_WARNING);
			}
		}

		return $this->error;

	}
}

?>
