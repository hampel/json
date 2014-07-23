<?php namespace Hampel\Json;

class JsonOptions
{
	public static function processDefaults($options, $defaults)
	{
		$options_return = array();

		foreach ($defaults as $name => $setting)
		{
			if (!isset($options[$name])) $options_return[$name] = $setting;
			else $options_return[$name] = $options[$name];
		}
		return $options_return;
	}

	public static function processOptions($options, $options_map)
	{
		$bitmask = 0;

		foreach ($options as $name => $value)
		{
			if ($value) $bitmask |= $options_map[$name];
		}

		return $bitmask;
	}
}

?>