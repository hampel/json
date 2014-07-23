<?php namespace Hampel\Json;

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