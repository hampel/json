<?php namespace Hampel\Json;

class JsonException extends \Exception
{
	public static $messages = array(
		JSON_ERROR_NONE => "No error has occurred",
		JSON_ERROR_DEPTH => "The maximum stack depth has been exceeded",
		JSON_ERROR_STATE_MISMATCH => "Invalid or malformed JSON",
		JSON_ERROR_CTRL_CHAR => "Control character error, possibly incorrectly encoded",
		JSON_ERROR_SYNTAX => "Syntax error",
		JSON_ERROR_UTF8 => "Malformed UTF-8 characters, possibly incorrectly encoded"
	);

	public function __construct($message = "", $code = 0, \Exception $previous = null)
	{
		if (array_key_exists($code, self::$messages)) $msg = self::$messages[$code];
		else $msg = "Unknown Error";

		if (!empty($message)) $message .= " ";
		$message .= $msg;

		parent::__construct($message, $code, $previous);
	}
}

?>