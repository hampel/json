CHANGELOG
=========

Unreleased
----------

Requires PHP 8.3 or later. `Json::encode()` and `Json::decode()` do the same job in the same way;
the consumer-visible changes are the PHP requirement, the signatures, and one bug fix.

**Breaking**

* minimum PHP raised to 8.3, replacing a `>=5.5.0` constraint that had not been tested since
  2014. PHP 8.1 is past end of security support, 8.2 reaches it on 31 December 2026, and the
  test toolchain cannot run below 8.1 in any case. Nothing is taken from anyone: Composer
  resolves older installs to 2.4.1, which goes on claiming `>=5.5.0`
* `Json` is now `final` — it is a static utility with no state and no extension points.
  `JsonException` stays extensible, since subclassing an exception is reasonable
* both methods carry native types: `encode(mixed $data, int $options = 0, int $depth = 512):
  string` and `decode(string $data, bool $assoc = false, int $depth = 512, int $options = 0):
  mixed`. An argument that cannot be coerced now raises a `TypeError` rather than reaching
  `json_encode()`
* `JsonException::__construct()` types `$previous` as `?\Throwable` rather than `\Exception`.
  This clears an implicit-nullable deprecation on PHP 8.4 and later, and allows an `\Error` to be
  chained, which the old hint prevented. A subclass overriding the constructor with an
  `\Exception` hint will need updating
* `JsonException::$messages` is typed `array`, and the class constants are typed
  (`public const bool DECODE_ASSOC`), which is what requires 8.3 specifically
* both classes declare `strict_types=1`. This governs calls made *from* these files; calling
  `Json::encode()` from a non-strict file still gets the usual coercion

**Fixed**

* `Json::decode('null')` threw instead of returning `null`. `null` is valid JSON, but a
  legitimate `null` result was indistinguishable from a failure, so decoding it raised a
  `JsonException` whose message read "Error decoding JSON: No error has occurred". `decode()`
  now relies solely on `json_last_error()`, which every `json_*` call resets and which is the
  authoritative signal. `false`, `0` and `''` were unaffected and remain so
* the docblocks on both methods opened with `/*` rather than `/**`, so neither static analysis
  nor an IDE had ever read them. Two of the documented types were wrong once visible:
  `decode()`'s `$options` was `array` where it is an `int` bitmask, and its return was `string`
  where it is `mixed`

**Internal**

* removed the `@` error-suppression operators from the `json_*` calls. Those functions emit no
  diagnostics on any supported PHP version, so the operators could only have masked a genuine
  error from elsewhere
* `!=` is now `!==`, and the low-precedence `OR` is now `||`

**Tooling**

* PSR-12 throughout, enforced by Laravel Pint, with a matching .editorconfig
* PHPStan at level 10, analysing `src` and `tests` against PHP 8.3 to 8.5 in a single pass via
  `phpVersion`
* PHPUnit updated from `~7.0|~8.0` to `^12.0`, config migrated to the 12.5 schema, and
  `failOnDeprecation` and `failOnNotice` enabled so a deprecation raised from `src` fails the run
* tests moved into a vendor-scoped `Hampel\Json\Tests` namespace, rather than a bare `Tests`
* covered the error paths that had no tests: recursion, unsupported type, control character, the
  encode-side depth limit, and the "Unknown Error" fallback. Seven of the nine `JSON_ERROR_*`
  codes are now exercised; `JSON_ERROR_NONE` is no longer reachable and
  `JSON_ERROR_STATE_MISMATCH` cannot be triggered through the public API
* GitHub Actions workflow running the suite on 8.3, 8.4 and 8.5, plus Pint, PHPStan and
  `composer validate --strict` once — on every push and on the first of each month
* `composer check` runs lint, analyse and test; `composer format` applies the style

**Packaging**

* repository moved from Bitbucket to GitHub; `support` and `homepage` URLs updated to match
* added LICENSE.md (MIT)
* `license` is the SPDX string `"MIT"` rather than a single-element array, which declared
  disjunctive licensing
* added .gitattributes: line endings normalised to LF, and tests, config and dotfiles kept out
  of the distributed archive with `export-ignore` — the archive holds README, CHANGELOG, LICENSE,
  composer.json and `src` only
* reworded the package description and removed the backticks, which Packagist rendered raw
* added `config.sort-packages`
* .gitignore now also covers .idea, .phpunit.cache and CLAUDE.local.md

**Documentation**

* README: removed the claim that v2.1, v2.2 and v2.3 are "maintained in parallel", untrue since
  2015, and replaced it with the actual PHP requirement. Added an "Upgrading from 2.x" section,
  reframed the note about `JSON_THROW_ON_ERROR` to say why the package is still here, and
  modernised the array syntax in the example
* README converted to GitHub-compatible markdown, with shields.io badges, and a CI badge
* updated the installation instructions and contact details
* added CLAUDE.md with build and test commands, architecture notes and conventions

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
