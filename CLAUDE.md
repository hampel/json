# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`hampel/json` — a Composer library published on Packagist. Two static-only classes wrap
`json_encode`/`json_decode` so that errors raise `Hampel\Json\JsonException` instead of returning
`false`/`null`, with `json_last_error()` codes translated to readable text.

The README notes the package is effectively obsolete since PHP 7.3 added `JSON_THROW_ON_ERROR`; it
is maintained for backwards compatibility, not extended. Treat changes as maintenance.

## Commands

```bash
composer install
vendor/bin/phpunit                                   # full suite (8 tests)
vendor/bin/phpunit --filter testDecodeBrokenStackDepth   # single test
vendor/bin/phpunit tests/JsonTest.php                # single file
```

PHPUnit 10 with `failOnRisky`/`failOnWarning` enabled, so a test that emits output or triggers a
warning fails.

## Architecture

- `src/Json.php` — `Json::encode()` / `Json::decode()`. Both suppress the native call with `@`, then
  inspect `json_last_error()`; a falsy/null return **or** a non-`JSON_ERROR_NONE` code throws.
  Consequence to keep in mind: `Json::decode('null')` throws, because a legitimate `null` result is
  indistinguishable from failure under this check. `DECODE_ASSOC`/`DECODE_OBJECT` constants exist
  only to make the `$assoc` argument readable at call sites.
- `src/JsonException.php` — constructor takes a *prefix* (`"Error decoding JSON:"`) and the
  `json_last_error()` code, then appends the matching text from the static `$messages` map (or
  `"Unknown Error"`). Tests assert against the full concatenated message, so editing a string in
  `$messages` breaks the corresponding test in `tests/JsonTest.php`.
- Test namespace is `Tests\` (autoload-dev), separate from `Hampel\Json\`.

## Conventions

Source targets PHP >= 5.5 (`composer.json`), so the code deliberately avoids anything newer — no
scalar/return types, no `??`, no arrow functions. It also uses tab indentation, Allman braces,
`<?php namespace Foo;` on one line, and the `OR` keyword. Match that style; do not modernise it
incidentally.

Line endings are LF, enforced by `.gitattributes` (`* text=auto eol=lf`) — the tree was previously
checked out through a CRLF smudge filter, which made every tracked file read as modified.

Update `CHANGELOG.md` (newest first, `x.y.z (YYYY-MM-DD)` heading) for any released change.
