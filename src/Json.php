<?php namespace Hampel\Json;

class Json
{
	/*
	 * Returns the JSON representation of a value
	 *
	 * @param mixed $data 	The data being encoded. Can be any type except a resource. Only works with UTF-8 encoded data
	 * @param int $options	Bitmask of json_encode options
	 *
	 * @return string Returns a JSON encoded string on success
	 *
	 * @throws JsonException
	 */
	public static function encode($data, $options = 0)
	{
		$json_data = @json_encode($data, $options);

		$json_error = json_last_error();

		if ($json_data === false OR $json_error != JSON_ERROR_NONE)
		{
			throw new JsonException("Error encoding JSON:", $json_error);
		}

		return $json_data;
	}

	/*
	 * Decodes a JSON string
	 *
	 * @param mixed $data 		The json string being decoded. Only works with UTF-8 encoded data
	 * @param bool $assoc		When TRUE, returned objects will be converted into associative arrays
	 * @param int $depth		User specified recursion depth
	 *
	 * @return string Returns the contents of the JSON encoded string as the appropriate PHP type on success
	 *
	 * @throws JsonException
	 */
	public static function decode($data, $assoc = false, $depth = 512)
	{
		$decoded_data = @json_decode($data, $assoc, $depth);

		$json_error = json_last_error();

		if (is_null($decoded_data) OR $json_error != JSON_ERROR_NONE)
		{
			throw new JsonException("Error decoding JSON:", $json_error);
		}

		return $decoded_data;
	}
}
