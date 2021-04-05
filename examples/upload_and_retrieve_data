<?php 

echo "<h1> Upload and Read vcf file, with debugging turned on and retrieve fullname, contact, email, photo & Address only</h1>";

// check readme to find all supported prefixes
$format =  "fn,ct,em,ph,ad";

// debug flag
$debug = true;

// is data flag
$data = false;

if (isset($_FILES['file'])) {
  
  // file 
  $vcftmp = $_FILES['file']['tmp_name'];
  
  if ( $vcard = new SimpleVCARD($vcftmp, $data, $format, $debug) ) {
    echo "<pre>";
    print_r( $vcard->records() );
    echo "</pre>";
  } else {
      echo SimpleVCARD::parseError();
  }
}

?>
