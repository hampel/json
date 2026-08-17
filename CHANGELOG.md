CHANGELOG
=========

Unreleased
----------

**Breaking**

* minimum PHP version is now 8.3
* `Json` is now final
* `Json::encode()` and `Json::decode()` declare native parameter and return types
* `JsonException::__construct()` types `$previous` as `?\Throwable` rather than `\Exception`
* `JsonException::$messages` and the class constants are typed
* both classes declare `strict_types=1`

**Fixed**

* `Json::decode('null')` returns `null` rather than throwing
* corrected the docblock types on `Json::decode()`: `$options` is an `int` bitmask, and the
  return is `mixed`

**Internal**

* removed the `@` error-suppression operators from the `json_*` calls
* `!=` is now `!==`, and `OR` is now `||`

**Tooling**

* PSR-12, enforced by Laravel Pint, with a matching .editorconfig
* PHPStan at level 10, analysing `src` and `tests` against PHP 8.3 to 8.5
* PHPUnit updated to `^12.0`, with `failOnDeprecation` and `failOnNotice` enabled
* tests moved to a vendor-scoped `Hampel\Json\Tests` namespace
* added tests for the untested error paths
* GitHub Actions workflow: the suite on PHP 8.3, 8.4 and 8.5, plus Pint, PHPStan and
  `composer validate --strict`, on every push and monthly
* `composer check` runs lint, analyse and test; `composer format` applies the style

**Packaging**

* repository moved from Bitbucket to GitHub
* added LICENSE.md (MIT)
* `license` is the SPDX string `"MIT"` rather than an array
* added .gitattributes: LF line endings, and `export-ignore` for tests, config and dotfiles
* added `config.sort-packages`
* .gitignore covers .idea, .phpunit.cache and CLAUDE.local.md
* reworded the package description

**Documentation**

* README states the PHP requirement and how to upgrade from 2.x
* README converted to GitHub-compatible markdown, with shields.io and CI badges
* added CLAUDE.md

2.4.1 (2019-10-14)
------------------

* PHPUnit v8 is also acceptable

2.4.0 (2019-05-02)
------------------

* update to PHPUnit v7

2.3.1 (2015-05-23)
------------------

* removed redundant closing php tags

2.3.0 (2014-12-17)
------------------

* added depth parameter to encode to allow usage of new PHP 5.5 parameter in json_encode
* added new error codes JSON_ERROR_RECURSION, JSON_ERROR_INF_OR_NAN and JSON_ERROR_UNSUPPORTED_TYPE to JsonException
* added new test for error while encoding NAN
* mininum PHP version set to 5.5.0 in composer.json

2.2.1 (2014-12-17)
------------------

* Merge branch 'feature/decode-const' of https://bitbucket.org/_mbfisher/json into decode-const ... add constants for
  DECODE_ASSOC and DECODE_OBJECT to make parameters more descriptive
* added unit test using new class constant

2.2.0 (2014-12-17)
------------------

* added $options parameter to decode, to support PHP 5.4.0 version of json_decode
* changed JsonException to use new PHP 5.4 shortened array syntax
* changed JsonTest unit tests to use new PHO 5.4 shortened array syntax
* mininum PHP version set to 5.4.0 in composer.json

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
