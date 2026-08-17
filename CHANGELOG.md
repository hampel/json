CHANGELOG
=========

Unreleased
----------

**Breaking**

* raised the minimum PHP version to 8.3, replacing the `>=5.5.0` constraint that had not been
  tested since 2014. PHP 8.1 is past end of security support and 8.2 reaches it on 31 December
  2026; the test toolchain cannot run below 8.1 in any case. Nothing is taken away from anyone —
  Composer resolves older installs to 2.4.1, which continues to claim `>=5.5.0`.

* `JsonException::__construct()` types `$previous` as `?\Throwable` rather than `\Exception`.
  This silences an implicit-nullable deprecation on PHP 8.4 and later, and allows an `\Error`
  to be chained, which the old hint prevented. It matches the parent `\Exception` signature. A
  subclass that overrides the constructor with an `\Exception` hint will need updating.

The behaviour of `Json::encode` and `Json::decode` is unchanged; everything else below is
packaging, tests or documentation.

**Packaging**

* repository moved from Bitbucket to GitHub; `support` and `homepage` URLs updated to match
* added LICENSE.md (MIT)
* added .gitattributes: line endings normalised to LF, and tests, phpunit.xml and the dotfiles
  excluded from the distributed archive with `export-ignore`
* reworded the package description and removed the backticks, which Packagist rendered raw
* .gitignore now also covers .idea, .phpunit.cache and CLAUDE.local.md
* `license` is now the SPDX string `"MIT"` rather than a single-element array, which declared
  disjunctive licensing
* added `config.sort-packages`, and `composer test`, `composer analyse` and `composer check`
  scripts
* added PHPStan at level 6, analysing `src` and `tests` against PHP 8.3 to 8.5 in a single run
  via `phpVersion`. phpstan.neon is excluded from the distributed archive
* corrected the docblocks on `Json::encode()` and `Json::decode()`, which opened with `/*`
  rather than `/**` and so were invisible to static analysis and IDEs. Two of the documented
  types were wrong once they became visible: `decode()`'s `$options` was `array` where it is a
  bitmask `int`, and its return was `string` where it is `mixed`

**Tests and tooling**

* PHPUnit updated from `~7.0|~8.0` to `^12.0`, via v9 and v10, with phpunit.xml migrated to the
  v12.5 schema. PHPUnit 12 requires PHP >= 8.3, matching the new floor
* `failOnDeprecation` and `failOnNotice` enabled, so a deprecation raised from `src` fails the
  run rather than being reported as an aside
* moved the tests into their own namespace, now vendor-scoped as `Hampel\Json\Tests` rather
  than a bare top-level `Tests`
* tests use `expectExceptionMessage` rather than asserting on the message by hand
* added CLAUDE.md with build and test commands, architecture notes and the conventions this
  package is written to

**Documentation**

* README converted to GitHub-compatible markdown, with shields.io badges
* updated the installation instructions and contact details

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
