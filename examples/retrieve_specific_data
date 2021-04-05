<?php 

echo "<h1> Read contacts.vcf, retrieve fullname, contact & Email only</h1>";

// check readme to find all supported prefixes
$format =  "fn,ct,em";

if ( $vcard = SimpleVCARD::retrieve("contact.vcf",$format) ) {
    echo "<pre>";
    print_r( $vcard->records );
    echo "</pre>";
 } else {
    echo SimpleVCARD::parseError();
 }

?>
