<?php namespace Hampel\Json;

class JsonException extends \Exception
{
	public static $messages = [
		JSON_ERROR_NONE => "No error has occurred",
		JSON_ERROR_DEPTH => "The maximum stack depth has been exceeded",
		JSON_ERROR_STATE_MISMATCH => "Invalid or malformed JSON",
		JSON_ERROR_CTRL_CHAR => "Control character error, possibly incorrectly encoded",
		JSON_ERROR_SYNTAX => "Syntax error",
		JSON_ERROR_UTF8 => "Malformed UTF-8 characters, possibly incorrectly encoded",
		JSON_ERROR_RECURSION => "The object or array passed to json_encode() include recursive references and cannot be encoded",
		JSON_ERROR_INF_OR_NAN => "The value passed to json_encode() includes either NAN or INF",
		JSON_ERROR_UNSUPPORTED_TYPE => "A value of an unsupported type was given to json_encode(), such as a resource",
	];

	public function __construct($message = "", $code = 0, \Exception $previous = null)
	{
		if (array_key_exists($code, self::$messages)) $msg = self::$messages[$code];
		else $msg = "Unknown Error";

		if (!empty($message)) $message .= " ";
		$message .= $msg;

		parent::__construct($message, $code, $previous);
	}
}
