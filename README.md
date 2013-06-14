Hampel Json
===========

A wrapper for json_encode and json_decode with error handling

By [Simon Hampel](http://hampelgroup.com/).

Installation
------------

The recommended way of installing Hampel Json is through [Composer](http://getcomposer.org):

    {
        "require": {
            "hampel/json": "dev-master"
        }
    }
    
Usage
-----

    <?php

    use Hampel\Json\Json;
    use Hampel\Json\JsonException;

    // Encode a variable as JSON:
    echo Json::encode(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5));

	// Encode options
	$options = array(
		'hex_tag' => true,
		'force_object' => true
	);
 	echo Json::encode(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5), $options);
 	
    // Decode JSON:
    print_r(Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5}'));

    // Error handling
    try {
        Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5'); // missing }
    } catch (JsonException $e) {
        echo "Oops: " . $e->getMessage();
    }
 
 Encoding Options
 ----------------
 
	Encoding options all default to FALSE. Set the corresponding option array key to TRUE to enable the corresponding json_encode bitmask option.
	When processing options, encode checks PHP version and only allows options permitted by that version.
	
	hex_tag: JSON_HEX_TAG
	hex_amp: JSON_HEX_AMP
	hex_apos: JSON_HEX_APOS
	hex_quot: JSON_HEX_QUOT
	force_object: JSON_FORCE_OBJECT
	numeric_check: JSON_NUMERIC_CHECK (PHP 5.3.3)
	pretty_print: JSON_PRETTY_PRINT (PHP 5.4.0)
	unescaped_slashes: JSON_UNESCAPED_SLASHES (PHP 5.4.0)
	unescaped_unicode: JSON_UNESCAPED_UNICODE (PHP 5.4.0)

 Decoding Options
 ----------------
	
	Decoding options all default to FALSE. Set the corresponding option array key to TRUE to enable the corresponding json_decode bitmask option.
	When processing options, decode checks PHP version and only allows options permitted by that version.
	Note that prior to PHP v5.4, the options parameter is ignored.
		
	bigint_as_string: (PHP 5.4.0)
	
