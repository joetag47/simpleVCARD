<?php 

echo "<h1> Read VCF Data Format </h1>";

$data =  "BEGIN:VCARD
          VERSION:2.1
          FN:John Doe
          TEL;CELL:+17030000000
          EMAIL;HOME:john.doe@email.com
          ADR;HOME:;;woodbug ;;;;
          END:VCARD";

if ( $vcard = SimpleVCARD::retrieve($data,"",true) ) {
    echo "<pre>";
    print_r( $vcard->records );
    echo "</pre>";
 } else {
    echo SimpleVCARD::parseError();
 }

?>
