Hampel Json
===========

A simple wrapper for json_encode and json_decode with error handling

By [Simon Hampel](http://hampelgroup.com/).

Installation
------------

The recommended way of installing Hampel Json is through [Composer](http://getcomposer.org):

    {
        "require": {
            "hampel/json": "~2.0"
        }
    }
    
Usage
-----

All parameters are the same as specified for the PHP functions json_encode and json_decode respectively.

The main difference that this class provides is that it throws exceptions when there are errors and translates the
error codes into meaningful text for you automatically.

    <?php

    use Hampel\Json\Json;
    use Hampel\Json\JsonException;

	$data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

    // Encode a variable as JSON:
    $json = new Json();
    echo $json->encode($data);

    // make it easier with the static wrapper
    echo Json::encode($data);

	// Encode options
	$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
    echo Json::encode($data, $options);

    // Decode JSON:
    print_r(Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5}'));

    // Error handling
    try {
        Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5'); // missing }
    } catch (JsonException $e) {
        echo "Oops: " . $e->getMessage();
    }
