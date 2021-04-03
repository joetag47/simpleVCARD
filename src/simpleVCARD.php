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
			$mime = mime_content_type($filename);

			// check file validity
			if ( ! is_readable( $filename ) ) {
				$this->error( 'File not found ' . $filename );

				return false;
			}

			// media check type
			else if ($mime !== "text/x-vcard"){
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
				$fields = explode("\r\n", $_split); 

				// template for specs
				$this->person = $this->specs;
				// set
				$this->person["contact"] = array();

				// cehck each field
				for ($i=0; $i < count($fields); $i++) { 

							
					if (strpos($fields[$i], "FN") === 0 ){
								
						$fn = explode(":", $fields[$i]);
						// 
						$this->person["fullname"] = $fn[1];
					}

					if (strpos($fields[$i], "TEL") === 0 ){
						$cn = explode(":", $fields[$i]);
						// 
						array_push($this->person["contact"], str_replace("-", "", $cn[1]));
					}

					if (strpos($fields[$i], "EMAIL") === 0 ){
						// 
						$em = explode(":", $fields[$i]);
						// 
						$this->person["email"] = $em[1];

					}

					if (strpos($fields[$i], "PHOTO") === 0 ){
						$ph = explode(":", $fields[$i]);
						// 
						$this->person["photo"] = $ph[1];
					}

					if (strpos($fields[$i], "ADR") === 0 ){
						$ad = explode(":", $fields[$i]);
						// 
						$this->person["address"] = str_replace(";", "", $ad[1]);
					}

					if (strpos($fields[$i], "ORG") === 0 ){
						$or = explode(":", $fields[$i]);
						// 
						$this->person["organization"] = str_replace(";", "", $or[1]);
					}

					if (strpos($fields[$i], "TITLE") === 0 ){
						$ti = explode(":", $fields[$i]);
						// 
						$this->person["title"] = str_replace(";", "", $ti[1]);
					}

					if (strpos($fields[$i], "URL") === 0 ){
						$ur = explode(":", $fields[$i]);
						// 
						$this->person["url"] = str_replace(";", "", $ur[1]);
					}

					if (strpos($fields[$i], "NOTE") === 0 ){
						$no = explode(":", $fields[$i]);
						// 
						$this->person["notes"] = str_replace(";", "", $no[1]);
					}

					if (strpos($fields[$i], "BDAY") === 0 ){
						$bd = explode(":", $fields[$i]);
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
