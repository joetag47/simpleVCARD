# SimpleVCARD Library 0.5 (Official)
vCalender PHP Reader, retrieves data from vCalender files, Supports v2.0, v3.0, v4.0

No additional extensions needed (relies on inbuilt PHP functions)

## Basic Usage
```php  
if ( $vcard = SimpleVCARD::retrieve("contacts.vcf") ) {
    print_r( $this->records );
 } else {
    echo SimpleVCARD::parseError();
 }
 ```
 ```
Array
(
    [0] => Array
        (
            [fullname] => John Doe
            [contact] => Array
                (
                    [0] => +44500000000
                    [1] => +44500000005
                )

            [email] => 
            [photo] => 
            [address] => 
            [organization] => 
            [title] => 
            [url] => 
            [notes] => 
            [birthday] => 
        )

    [1] => Array
        (
            [fullname] => Ciara fingers
            [contact] => Array
                (
                    [0] => +233240000000
                    [1] => +233500000000
                )

            [email] => 
            [photo] => 
            [address] => 
            [organization] => 
            [title] => 
            [url] => 
            [notes] => 
            [birthday] => 1990-12-01
        )

    [2] => Array
        (
            [fullname] => Joe Public
            [contact] => Array
                (
                    [0] => +17030000000
                )

            [email] => 
            [photo] => 
            [address] => 
            [organization] => 
            [title] => 
            [url] => 
            [notes] => 
            [birthday] => 
        )

)

## Installation

Clone git repository https://github.com/joetag47/simpleVCARD/
