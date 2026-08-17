<?php

declare(strict_types=1);

namespace Hampel\Json;

final class Json
{
    public const bool DECODE_ASSOC = true;
    public const bool DECODE_OBJECT = false;

    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $data The data being encoded. Can be any type except a resource. Only works with UTF-8 encoded data
     * @param int $options Bitmask of json_encode options
     * @param int<1, max> $depth Maximum depth; json_encode() rejects zero or less
     *
     * @return string Returns a JSON encoded string on success
     *
     * @throws JsonException
     */
    public static function encode(mixed $data, int $options = 0, int $depth = 512): string
    {
        $json_data = json_encode($data, $options, $depth);

        $json_error = json_last_error();

        if ($json_data === false || $json_error !== JSON_ERROR_NONE) {
            throw new JsonException("Error encoding JSON:", $json_error);
        }

        return $json_data;
    }

    /**
     * Decodes a JSON string
     *
     * @param string $data The json string being decoded. Only works with UTF-8 encoded data
     * @param bool $assoc When TRUE, returned objects will be converted into associative arrays
     * @param int<1, max> $depth User specified recursion depth; json_decode() rejects zero or less
     * @param int $options Bitmask of json_decode options
     *
     * @return mixed Returns the contents of the JSON encoded string as the appropriate PHP type on success
     *
     * @throws JsonException
     */
    public static function decode(string $data, bool $assoc = self::DECODE_OBJECT, int $depth = 512, int $options = 0): mixed
    {
        $decoded_data = json_decode($data, $assoc, $depth, $options);

        $json_error = json_last_error();

        if ($json_error !== JSON_ERROR_NONE) {
            throw new JsonException("Error decoding JSON:", $json_error);
        }

        return $decoded_data;
    }
}
