# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`hampel/json` — a Composer library published on Packagist. Two static-only classes wrap
`json_encode`/`json_decode` so that errors raise `Hampel\Json\JsonException` instead of returning
`false`/`null`, with `json_last_error()` codes translated to readable text.

PHP 7.3 added `JSON_THROW_ON_ERROR`, which covers most of what this package does, and the README
says so. It is kept anyway, deliberately: it is small, complete, and several other `hampel/*`
packages depend on it. Treat changes as maintenance — the API is not being extended.

## Commands

```bash
composer install
vendor/bin/phpunit                                       # full suite
vendor/bin/phpunit --filter testDecodeBrokenStackDepth   # single test
vendor/bin/phpunit tests/JsonTest.php                    # single file
php8.5 vendor/bin/phpunit                                # ceiling of the supported range
```

`composer check` runs PHPStan then the suite — the same two things CI runs, so a green check
locally means a green build.

PHPUnit 12 with `failOnRisky`, `failOnWarning`, `failOnDeprecation` and `failOnNotice` enabled, so
a test that emits output, or code in `src` that triggers a deprecation, fails the run. Note that
`failOnDeprecation` only covers paths listed in `<source>`.

## Architecture

- `src/Json.php` — `Json::encode()` / `Json::decode()`. Both suppress the native call with `@` and
  then inspect `json_last_error()`, which is the authoritative failure signal because it is reset
  by every `json_*` call. `decode()` relies on it alone; do not reintroduce a check on the decoded
  value, since `null`, `false`, `0` and `''` are all legitimate results (an `is_null()` check here
  made `Json::decode('null')` throw an exception reading "No error has occurred", fixed in 3.0.0).
  `DECODE_ASSOC`/`DECODE_OBJECT` constants exist only to make the `$assoc` argument readable at
  call sites.
- `src/JsonException.php` — constructor takes a *prefix* (`"Error decoding JSON:"`) and the
  `json_last_error()` code, then appends the matching text from the static `$messages` map (or
  `"Unknown Error"`). Tests assert against the full concatenated message, so editing a string in
  `$messages` breaks the corresponding test in `tests/JsonTest.php`.
- Test namespace is `Hampel\Json\Tests\` (autoload-dev), mapped to `tests/`.

## Conventions

The source is written in a pre-7.0 idiom: tab indentation, Allman braces, `<?php namespace Foo;`
on one line, the `OR` keyword, and no type declarations. That is history, not a requirement — the
floor is now PHP >= 8.3 (`composer.json`), which permits all of it.

Converting to a modern style is a deliberate decision that has not been taken. Until it is, match
the surrounding style rather than mixing idioms within a file, and do not modernise incidentally.

Update `CHANGELOG.md` (newest first, `x.y.z (YYYY-MM-DD)` heading) for any released change.
