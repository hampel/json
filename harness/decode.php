<?php

/**
 * Exercise: what Json::decode() returns, including the returns that look like failures.
 *
 * Needs no credentials and touches nothing outside this process.
 *
 * The interesting part is the falsy section: null, false, 0 and '' are all legitimate
 * decoded values, so the decoded value cannot be used to detect an error. json_last_error()
 * is the only authoritative signal, and it is what decode() checks.
 *
 * @var Hampel\Rig\Io $io
 */

use Hampel\Json\Json;

$io->title('json · decode');

$json = '{"a":1,"b":[2,3],"c":{"d":true}}';

$io->info('  $assoc chooses the shape you get back');
$io->line();

$io->value('json', $json);
$io->value('default', Json::decode($json));
$io->value('DECODE_OBJECT', Json::decode($json, Json::DECODE_OBJECT));
$io->value('DECODE_ASSOC', Json::decode($json, Json::DECODE_ASSOC));

$io->line();
$io->info('  the constants exist to make the argument readable at the call site');
$io->line();

$io->value('DECODE_ASSOC', Json::DECODE_ASSOC);
$io->value('DECODE_OBJECT', Json::DECODE_OBJECT);

$io->line();
$io->info('  falsy values decode successfully - none of these is an error');
$io->line();

foreach (['null', 'false', 'true', '0', '""', '[]', '{}'] as $literal) {
    $decoded = Json::decode($literal);

    $io->value($literal, $decoded);
}

$io->line();
$io->info('  which is why the decoded value cannot be the error signal');
$io->line();

$io->value('decode(null)', Json::decode('null'));
$io->value('is_null', is_null(Json::decode('null')));
$io->value('last error', json_last_error_msg());

$io->line();
$io->info('  $depth, where decode() and encode() do not agree');
$io->line();

/*
 * The same three-level structure encodes at depth 3 but needs depth 4 to decode. That
 * asymmetry is PHP's, not this package's - both arguments are passed straight through -
 * but it is the kind of thing you only find by running it, so it is here to be seen.
 */
$structure = ['a' => ['b' => ['c' => 1]]];
$json = Json::encode($structure);

$io->value('structure', $structure);
$io->value('json', $json);
$io->line();

foreach ([2, 3, 4] as $depth) {
    $io->attempt("encode at depth {$depth}", fn (): string => Json::encode($structure, 0, $depth));
}

$io->line();

foreach ([2, 3, 4] as $depth) {
    $io->attempt("decode at depth {$depth}", fn (): mixed => Json::decode($json, Json::DECODE_ASSOC, $depth));
}

$io->line();
$io->info('  $options - JSON_BIGINT_AS_STRING keeps precision PHP ints cannot hold');
$io->line();

$bigint = '{"id":12345678901234567890}';

$io->value('json', $bigint);
$io->value('default', Json::decode($bigint, Json::DECODE_ASSOC));
$io->value('as string', Json::decode($bigint, Json::DECODE_ASSOC, 512, JSON_BIGINT_AS_STRING));

$io->line();
$io->info('  the error paths');
$io->line();

$io->attempt('a truncated object', fn (): mixed => Json::decode('{"a":1'));
$io->attempt('mismatched brackets', fn (): mixed => Json::decode('{]'));
$io->attempt('a raw control character', fn (): mixed => Json::decode("\"a\x01b\""));
$io->attempt('malformed UTF-8', fn (): mixed => Json::decode("\"\xB1\x31\""));
$io->attempt('an empty string', fn (): mixed => Json::decode(''));
