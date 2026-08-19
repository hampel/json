<?php

/**
 * Exercise: what Json::encode() produces, and what it refuses to.
 *
 * Needs no credentials and touches nothing outside this process. The unit tests assert
 * that the return values have not changed; this shows you the JSON itself, which is the
 * part you actually have to look at to know whether it is what you wanted.
 *
 * @var Hampel\Rig\Io $io
 */

use Hampel\Json\Json;

$io->title('json · encode');

$data = ['a' => 1, 'b' => 2, 'c' => 3];

$io->info('  the defaults');
$io->line();

$io->value('array', Json::encode($data));
$io->value('object', Json::encode((object) $data));
$io->value('list', Json::encode([1, 2, 3]));
$io->value('int', Json::encode(42));
$io->value('null', Json::encode(null));
$io->value('empty array', Json::encode([]));

$io->line();
$io->info('  $options is the json_encode flag bitmask, passed straight through');
$io->line();

$payload = [
    'url' => 'https://example.test/a/b',
    'note' => '<a href="x">Tom & Jerry\'s</a>',
    'name' => 'naïve',
];

$io->value('none', Json::encode($payload));
$io->value('hex flags', Json::encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP));
$io->value('slashes', Json::encode($payload, JSON_UNESCAPED_SLASHES));
$io->value('unicode', Json::encode($payload, JSON_UNESCAPED_UNICODE));

$io->line();
$io->info('  JSON_PRETTY_PRINT, which is worth seeing at full width');
$io->line();
$io->line(Json::encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

$io->line();
$io->info('  $depth counts nesting levels; exceeding it is an error, not a truncation');
$io->line();

$nested = ['a' => ['b' => ['c' => 'value']]];

$io->attempt('depth 3, deep enough', fn (): string => Json::encode($nested, 0, 3));
$io->attempt('depth 2, not deep enough', fn (): string => Json::encode($nested, 0, 2));

$io->line();
$io->info('  the error paths - the reason this package exists');
$io->line();

$recursive = new stdClass();
$recursive->self = $recursive;

$io->attempt('NAN', fn (): string => Json::encode(NAN));
$io->attempt('INF', fn (): string => Json::encode(INF));
$io->attempt('a recursive reference', fn (): string => Json::encode($recursive));
$io->attempt('a resource', fn (): string => Json::encode(fopen('php://memory', 'r')));
$io->attempt('malformed UTF-8', fn (): string => Json::encode("\xB1\x31"));

$io->line();
$io->info('  native json_encode() signals the same failures by returning false');
$io->line();

$io->value('native', json_encode(NAN));
$io->value('last error', json_last_error_msg());
