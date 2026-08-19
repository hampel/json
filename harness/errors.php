<?php

/**
 * Exercise: every error this package can report, triggered for real.
 *
 * Needs no credentials and touches nothing outside this process.
 *
 * Translating json_last_error() into text a person can read is the whole point of the
 * package, so the text is the thing worth looking at. Each entry below is produced by an
 * actual failing call rather than by constructing the exception, which is what makes this
 * a check that the mapping still matches what PHP reports.
 *
 * @var Hampel\Rig\Io $io
 */

use Hampel\Json\Json;
use Hampel\Json\JsonException;

$io->title('json · errors');

$io->info('  the map: json_last_error() code to text');
$io->line();

foreach (JsonException::$messages as $code => $text) {
    $io->value((string) $code, $text);
}

$io->line();
$io->info('  JSON_ERROR_NONE is in the map but is never thrown - a call that sets it succeeded');

$io->line();
$io->info('  each code, from a call that really fails');
$io->line();

$recursive = new stdClass();
$recursive->self = $recursive;

/** @var array<string, callable> $triggers */
$triggers = [
    'DEPTH' => fn (): string => Json::encode(['a' => ['b' => ['c' => 1]]], 0, 2),
    'STATE_MISMATCH' => fn (): mixed => Json::decode('{]'),
    'CTRL_CHAR' => fn (): mixed => Json::decode("\"a\x01b\""),
    'SYNTAX' => fn (): mixed => Json::decode('{"a":1'),
    'UTF8' => fn (): string => Json::encode("\xB1\x31"),
    'RECURSION' => fn (): string => Json::encode($recursive),
    'INF_OR_NAN' => fn (): string => Json::encode(NAN),
    'UNSUPPORTED_TYPE' => fn (): string => Json::encode(fopen('php://memory', 'r')),
];

foreach ($triggers as $label => $trigger) {
    $io->attempt($label, $trigger);
}

$io->line();
$io->info('  the prefix tells you which way the call was going');
$io->line();

$io->value('encoding', (new JsonException('Error encoding JSON:', JSON_ERROR_UTF8))->getMessage());
$io->value('decoding', (new JsonException('Error decoding JSON:', JSON_ERROR_UTF8))->getMessage());
$io->value('no prefix', (new JsonException('', JSON_ERROR_UTF8))->getMessage());

$io->line();
$io->info('  a code with no entry in the map falls back rather than failing');
$io->line();

$io->value('code 999', (new JsonException('Error decoding JSON:', 999))->getMessage());

$io->line();
$io->info('  JsonException is an ordinary exception - it chains and it carries the code');
$io->line();

$chained = new JsonException('Error decoding JSON:', JSON_ERROR_SYNTAX, new RuntimeException('the cause'));

$io->value('exception', $chained);
$io->value('extends', get_parent_class($chained));
$io->value('code', $chained->getCode());
$io->value('previous', $chained->getPrevious());
