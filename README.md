# SimpleVCARD Library 0.5.1 (Official)
vCalender PHP Reader, retrieves data from vCalender files, Supports v2.0, v3.0, v4.0

No additional extensions needed (relies on inbuilt PHP functions).

**Joshua Tagoe** <hommie500@gmail.com> 2021

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
```
### For more advanced usage
SimpleVCARD constructor takes four parameters 
_The basic usage only takes one parameter._

1. **filename** | string | (.vcf) or data(vcard format);

2. **options** | seperated with comma. Prefix includes; _default is set to empty string for all options_  
    -  fn  for fullname,
	-  ct  for contact,
	-  em  for email,
	-  ph  for photo,
	-  ad  for address,
	-  org  for organization,
	-  ti  for title,
	-  ur  for url,
	-  no  for notes,
	-  bd  for birthday

3. **data_flag** | boolean (true or false) | true for when filename is data, false when filename points to a file | _default is set to false_

4. **debug_flag** | boolean (true or false) | true to display errors, false to hide errors | _default is set to false_

// new SimpleVCARD($filename, $options, $data_flag, $debug_flag);
```

// Examples
// $vcard = new SimpleVCARD("contacts.vcf","fn,ct,em",false,true);
// $vcard = new SimpleVCARD("vcard contact format","",true,false);
// $vcard = new SimpleVCARD("contacts.vcf","fn,ct,em");

```
## Installation

Clone git repository :: https://github.com/joetag47/simpleVCARD/

```php
   
 // include path in project  
 include_once 'simple-vcard/src/simpleVCARD.php;
 
 ```
