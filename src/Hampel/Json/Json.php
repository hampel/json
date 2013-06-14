<?php namespace Hampel\Json;

class Json
{
	private static function getEncodeOptionsMap()
	{
		$options_map = array(
			'hex_tag' => JSON_HEX_TAG,
			'hex_amp' => JSON_HEX_AMP,
			'hex_apos' => JSON_HEX_APOS,
			'hex_quot' => JSON_HEX_QUOT,
			'force_object' => JSON_FORCE_OBJECT,
		);

		if (version_compare(PHP_VERSION, '5.3.3') >= 0)
		{
			$options_map['numeric_check'] = JSON_NUMERIC_CHECK;
		}

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$options_map['pretty_print'] = JSON_PRETTY_PRINT;
			$options_map['unescaped_slashes'] = JSON_UNESCAPED_SLASHES;
			$options_map['unescaped_unicode'] = JSON_UNESCAPED_UNICODE;
		}

		return $options_map;
	}

	private static function getEncodeDefaults()
	{
		$defaults = array(
			'hex_tag' => false,
			'hex_amp' => false,
			'hex_apos' => false,
			'hex_quot' => false,
			'force_object' => false,
		);

		if (version_compare(PHP_VERSION, '5.3.3') >= 0)
		{
			$defaults['numeric_check'] = false;
		}

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$defaults['pretty_print'] = false;
			$defaults['unescaped_slashes'] = false;
			$defaults['unescaped_unicode'] = false;
		}

		return $defaults;
	}

	/*
	 * Returns the JSON representation of a value
	 *
	 * @param mixed $data 		The data being encoded. Can be any type except a resource. Only works with UTF-8 encoded data
	 * @param array $options	Array of option flags
	 *
	 * @return string Returns a JSON encoded string on success
	 *
	 * @throws JsonException
	 */
	public static function encode($data, $options = array())
	{
		$options = JsonOptions::processDefaults($options, self::getEncodeDefaults());
		$bitmask_options = JsonOptions::processOptions($options, self::getEncodeOptionsMap());

		$json_data = @json_encode($data, $bitmask_options);

		if ($json_data === false OR ($json_error = json_last_error()) != JSON_ERROR_NONE)
		{
			throw new JsonException($json_error, "Error encoding JSON:");
		}

		return $json_data;
	}

	private static function getDecodeOptionsMap()
	{
		$options_map = array();

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$options_map['bigint_as_string'] = JSON_BIGINT_AS_STRING;
		}

		return $options_map;
	}

	private static function getDecodeDefaults()
	{
		$defaults = array();

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$defaults['bigint_as_string'] = false;
		}

		return $defaults;
	}

	/*
	 * Decodes a JSON string
	 *
	 * @param mixed $data 		The json string being decoded. Only works with UTF-8 encoded data
	 * @param bool $assoc		When TRUE, returned objects will be converted into associative arrays
	 * @param int $depth		User specified recursion depth
	 * @param array $options	Array of option flags
	 *
	 * @return string Returns the contents of the JSON encoded string as the appropriate PHP type on success
	 *
	 * @throws JsonException
	 */
	public static function decode($data, $assoc = false, $depth = 512, $options = array())
	{
		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$options = JsonOptions::processDefaults($options, self::getDecodeDefaults());
			$bitmask_options = JsonOptions::processOptions($options, self::getDecodeOptionsMap());

			$decoded_data = json_decode($data, $assoc, $depth, $bitmask_options);
		}
		else
		{
			$decoded_data = json_decode($data, $assoc, $depth);
		}

		if (is_null($decoded_data) AND ($json_error = json_last_error()) != JSON_ERROR_NONE)
		{
			throw new JsonException($json_error, "Error decoding JSON:");
		}

		return $decoded_data;
	}

}

?>