JSON Wrapper
============

[![Tests](https://github.com/hampel/json/actions/workflows/tests.yml/badge.svg)](https://github.com/hampel/json/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)
[![Total Downloads](https://img.shields.io/packagist/dt/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)
[![Open Issues](https://img.shields.io/github/issues-raw/hampel/json.svg?style=flat-square)](https://github.com/hampel/json/issues)
[![License](https://img.shields.io/packagist/l/hampel/json.svg?style=flat-square)](https://packagist.org/packages/hampel/json)

A simple wrapper for `json_encode` and `json_decode` with exception based error handling

By [Simon Hampel](mailto:simon@hampelgroup.com)

__Note:__ since PHP v7.3, the native functions accept a `JSON_THROW_ON_ERROR` flag which covers most of what this
package does. This package remains for the cleaner call signature, for the readable error messages it produces, and
for the packages that already depend on it. For new code, the native flag is usually the better choice.

Installation
------------

To install using composer, run the following command:

`composer require hampel/json`

Requires PHP 8.3 or later.

Older versions of PHP resolve to earlier releases automatically — the 2.x line supports PHP 5.5 and later, and remains
installable. It is no longer maintained.

Upgrading from 2.x
------------------

* PHP 8.3 is now the minimum. Nothing changes for consumers who stay on 2.4.1
* `Json::decode('null')` returns `null` instead of throwing. `null` is valid JSON, but a legitimate `null` result used
  to be indistinguishable from a failure, so it raised an exception reading "No error has occurred"
* `Json` is now `final`. `JsonException` is still extensible
* both methods declare native parameter and return types, so an argument that cannot be coerced now raises a
  `TypeError` rather than reaching `json_encode()`

Usage
-----

All parameters are the same as specified for the PHP functions json_encode and json_decode respectively.

The main difference that this class provides is that it throws exceptions when there are errors and translates the
error codes into meaningful text for you automatically.

```php
<?php

use Hampel\Json\Json;
use Hampel\Json\JsonException;

$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

// Encode a variable as JSON:
echo Json::encode($data);

// Encode options - escape characters that are unsafe in HTML
$html = ['note' => '<a href="x">Tom & Jerry\'s</a>'];
$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

echo Json::encode($html);
// {"note":"<a href=\"x\">Tom & Jerry's<\/a>"}

echo Json::encode($html, $options);
// {"note":"\u003Ca href=\u0022x\u0022\u003ETom \u0026 Jerry\u0027s\u003C\/a\u003E"}

// Decode JSON:
print_r(Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5}'));

// Error handling
try {
    Json::decode('{"a":1,"b":2,"c":3,"d":4,"e":5'); // missing }
} catch (JsonException $e) {
    echo "Oops: " . $e->getMessage();
}
```
