Hampel Json
===========

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)
[![Total Downloads](https://img.shields.io/packagist/dt/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)
[![Open Issues](https://img.shields.io/github/issues-raw/hampel/json.svg?style=flat-square)](https://github.com/hampel/json/issues)
[![License](https://img.shields.io/packagist/l/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)

A simple wrapper for `json_encode` and `json_decode` with exception based error handling

By [Simon Hampel](https://twitter.com/SimonHampel).

__Note:__ as of PHP v7.3 there is now a `JSON_THROW_ON_ERROR` option for both native commands which should effectively
render this package obsolete.

Installation
------------

The recommended way of installing Hampel Json is through [Composer](http://getcomposer.org):

    {
        "require": {
            "hampel/json": "^2.0"
        }
    }

Note that there are three versions of this package, depending on the version of PHP you use:

* v2.1 supports PHP >= v5.3.3
* v2.2 supports PHP >= v5.4.0
* v2.3 supports PHP >= v5.5.0

The three versions will be maintained in parallel
    
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
