<?php namespace Hampel\Json;

class Json
{
	private static function process_defaults($options, $defaults)
	{
		$options_return = array();

		foreach ($defaults as $name => $setting)
		{
			if (!isset($options[$name])) $options_return[$name] = $defaults[$name];
			else $options_return[$name] = $setting;
		}
		return $options_return;
	}

	private static function process_encode_options($options)
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
			$options_map['bigint_as_string'] = JSON_BIGINT_AS_STRING;
			$options_map['pretty_print'] = JSON_PRETTY_PRINT;
			$options_map['unescaped_slashed'] = JSON_UNESCAPED_SLASHES;
			$options_map['unescaped_unicode'] = JSON_UNESCAPED_UNICODE;
		}

		$bitmask = 0;

		foreach ($options as $name => $value)
		{
			if ($value) $bitmask |= $options_map[$name];
		}

		return $bitmask;
	}

	public static function encode($data, $options = array())
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
			$defaults['bigint_as_string'] = false;
			$defaults['pretty_print'] = false;
			$defaults['unescaped_slashed'] = false;
			$defaults['unescaped_unicode'] = false;
		}

		$options = self::process_defaults($options, $defaults);

		$json_data = json_encode($data, self::process_encode_options($options));

		if ($json_data === false AND ($json_error = json_last_error()) != JSON_ERROR_NONE)
		{
			throw new JsonException($json_error, "Error encoding JSON:");
		}

		return $json_data;
	}

	private static function process_decode_options($options)
	{
		$options_map = array();

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$options_map['bigint_as_string'] = JSON_BIGINT_AS_STRING;
		}

		$bitmask = 0;

		foreach ($options as $name => $value)
		{
			if ($value) $bitmask |= $options_map[$name];
		}

		return $bitmask;
	}

	public static function decode($data, $assoc = false, $depth = 512, $options = array())
	{
		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$defaults['bigint_as_string'] = false;
		}

		$options = self::process_defaults($options, $defaults);

		$decoded_data = json_decode($data, $assoc, $depth, self::process_decode_options($options));

		if (is_null($decoded_data) AND ($json_error = json_last_error()) != JSON_ERROR_NONE)
		{
			throw new JsonException($json_error, "Error decoding JSON:");
		}

		return $decoded_data;
	}

}

class JsonException extends \Exception
{
	public function __construct($error_code, $message = "", Exception $previous = null)
	{
		$error_messages = array(
			JSON_ERROR_NONE => "No error has occurred",
			JSON_ERROR_DEPTH => "The maximum stack depth has been exceeded",
			JSON_ERROR_STATE_MISMATCH => "Invalid or malformed JSON",
			JSON_ERROR_CTRL_CHAR => "Control character error, possibly incorrectly encoded",
			JSON_ERROR_SYNTAX => "Syntax error",
			JSON_ERROR_UTF8 => "Malformed UTF-8 characters, possibly incorrectly encoded"
		);

		if (array_key_exists($error_code, $error_messages)) $msg = $error_messages[$error_code];
		else $msg = "Unknown Error";

		if ($message) $message .= " ";
		$message .= $msg;

		parent::__construct($message, $error_code, $previous);
	}
}

?>