CHANGELOG
=========

2.1.1 (2014-12-17)
------------------

* removed unneeded docblock param $options for Json::decode

2.1.0 (2014-12-17)
------------------

* remove code not compatible with PHP 5.3
* change methods to be static
* split JsonException::$messages into static variable, removed things not supported by PHP 5.3
* updated tests to use new static-only methods
* there is one property which was introduced in PHP v5.3.3, so we'll use that as our minimum version in composer.json

2.0.0 (2014-07-24)
------------------

* major rewrite - vastly more simple, just a very basic wrapper now which throws exceptions for errors

1.0.3 (2014-07-23)
------------------

* converted to psr-4 autoloading
* added phpunit back in to dev dependencies - can't assume other people will have phpunit installed for unit testing

1.0.2 (2014-06-01)
------------------

* removed dev dependency on phpunit

1.0.1 (2013-10-14)
------------------

* slight change to fix broken phpunit test

1.0.0 (2013-08-28)
------------------

* upgrading this package to stable
* updated composer.json
* updated README
* added CHANGELOG

0.1.1 (2013-06-14)
------------------

* fixed problem with composer.json

0.1.0 (2013-06-14)
------------------

* initial release