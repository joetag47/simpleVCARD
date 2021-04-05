<?php 

echo "<h1> Read contacts.vcf file</h1>";

if ( $vcard = SimpleVCARD::retrieve("contacts.vcf") ) {
    echo "<pre>";
    print_r( $vcard->records );
    echo "</pre>";
 } else {
    echo SimpleVCARD::parseError();
 }

?>
