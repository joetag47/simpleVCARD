# SimpleVCARD Library 0.5.2 (Official)
[<img src="https://img.shields.io/badge/license-MIT-success" />](https://github.com/joetag47/simpleVCARD/blob/main/LICENSE) [<img src="https://img.shields.io/badge/issues-0-important" />](https://github.com/joetag47/simpleVCARD/issues)

vCalender PHP Reader, retrieves data from vCalender files, Supports v2.0, v3.0, v4.0

No additional extensions needed (relies on inbuilt PHP functions).

**Joshua Tagoe** <hommie500@gmail.com> 2021

## Basic Usage
```php  
if ( $vcard = SimpleVCARD::retrieve("contacts.vcf") ) {
    print_r( $vcard->records );
 } else {
    echo SimpleVCARD::parseError();
 }
 
 // data is stored in records | an associative array or retrieve with records() method
 $vcard->records 
 $vcard->records() 
 
 ```
 ```php
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
SimpleVCARD constructor takes four parameters.
_The basic usage only takes one parameter. Eg_``` SimpleVCARD($filename) ```

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


```php
// format
// SimpleVCARD($filename, $options, $data_flag, $debug_flag);

// Examples
// $vcard = new SimpleVCARD("contacts.vcf","fn,ct,em",false,true);
// $vcard = new SimpleVCARD("vcard contact format","",true,false);
// $vcard = new SimpleVCARD("contacts.vcf","fn,ct,em");

```
Find more examples here [click here](https://github.com/joetag47/simpleVCARD/tree/main/examples)

## To Json
Render data in JSON format.

```php

// render data in JSON format
$vcard = SimpleVCARD::retrieve("contacts.vcf") );
echo ( $vcard->toJSON() );

```

## To HTML
Render data in HTML, displays data in table with their respective columns.
The method ``` $vard->toHTML() ``` takes two parameters id feild and class feild, default for both is nothing.


```php

// render data in HTML format - simple
$vcard = SimpleVCARD::retrieve("contacts.vcf") );

// echo table
echo ( $vcard->toHTML() );


// render data in HTML format - extra
$vcard = SimpleVCARD::retrieve("contacts.vcf", "fn,ct,em") );

// echo table. Id and class(es) should not contain # or . use spaces to seperate different class names
echo ( $vcard->toHTML("id", "class1 or class2") );

```

## Debug 

```php  

// enable error reporting
ini_set('error_reporting', E_ALL );
ini_set('display_errors', 1 );

if ( $vcard = SimpleVCARD::retrieve("contacts.vcf", "", false, true) ) {
    print_r( $vcard->records );
 } else {
    echo SimpleVCARD::parseError();
 }
 
 ```
 
## Classic OOP Style

 ```php  
// pass true true
if ( $vcard = new SimpleVCARD("contacts.vcf") ) {
	echo "<pre>";
    	  print_r( $vcard->records() );
    	echo "</pre>";
 } else {
    echo "Error : ".$vcard->error();
 }
 
 ```

## Installation

Clone git repository : [click here](https://github.com/joetag47/simpleVCARD/archive/refs/heads/main.zip)

```php
   
 // include path in project  
 include_once 'simple-vcard/src/simpleVCARD.php;
 
 ```


## Error Codes

SimpleVCARD::ParseError(), $vcard->error()

<table>
<tr><th>Error code</th><th>Message</th><th>Comment</th></tr>
<tr><td>1</td><td>File not found</td><td>location of file?</td></tr>
<tr><td>2</td><td>Invalid File</td><td>not a vcard</td></tr>
<tr><td>3</td><td>Data is invalid</td><td>not vcard data</td></tr>
<tr><td>4</td><td>Invalid string format</td><td>not a vcard</td></tr>
<tr><td>5</td><td>Specification String is invalid, format</td><td> Must look like e.g fn,em,cn etc!.</td></tr>
<tr><td>6</td><td>Specification Prefix is invalid</td><td>must look like e.g fn or em or cn</td></tr>
</table>	


## History

```
v0.5.2 (2021-04-11) : Fixed support for linux (.vcf) file reading.
v0.5.3 (2021-06-01) : Fixed support for vcard version 3.0.
```
