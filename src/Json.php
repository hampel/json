<?php

namespace Hampel\Json;

class Json
{
    public const DECODE_ASSOC = true;
    public const DECODE_OBJECT = false;

    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $data 	The data being encoded. Can be any type except a resource. Only works with UTF-8 encoded data
     * @param int $options	Bitmask of json_encode options
     * @param int $depth    Maximum depth
     *
     * @return string Returns a JSON encoded string on success
     *
     * @throws JsonException
     */
    public static function encode($data, $options = 0, $depth = 512)
    {
        $json_data = @json_encode($data, $options, $depth);

        $json_error = json_last_error();

        if ($json_data === false or $json_error != JSON_ERROR_NONE) {
            throw new JsonException("Error encoding JSON:", $json_error);
        }

        return $json_data;
    }

    /**
     * Decodes a JSON string
     *
     * @param string $data 		The json string being decoded. Only works with UTF-8 encoded data
     * @param bool $assoc		When TRUE, returned objects will be converted into associative arrays
     * @param int $depth		User specified recursion depth
     * @param int $options      Bitmask of json_decode options
     *
     * @return mixed Returns the contents of the JSON encoded string as the appropriate PHP type on success
     *
     * @throws JsonException
     */
    public static function decode($data, $assoc = self::DECODE_OBJECT, $depth = 512, $options = 0)
    {
        $decoded_data = @json_decode($data, $assoc, $depth, $options);

        $json_error = json_last_error();

        if ($json_error != JSON_ERROR_NONE) {
            throw new JsonException("Error decoding JSON:", $json_error);
        }

        return $decoded_data;
    }
}
