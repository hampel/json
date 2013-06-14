<?php namespace Hampel\Json;

class JsonOptionsTest extends \PHPUnit_Framework_TestCase
{
	public function testProcessDefaults()
	{
		$options = array(
			'hex_quot' => true,
			'hex_amp' => true,
			'force_object' => true
		);

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

		$options = JsonOptions::processDefaults($options, $defaults);

		$this->assertArrayHasKey('hex_tag', $options);
		$this->assertFalse(array_key_exists('hex_tag', $options) AND $options['hex_tag']);

		$this->assertArrayHasKey('hex_amp', $options);
		$this->assertTrue(array_key_exists('hex_amp', $options) AND $options['hex_amp']);

		$this->assertArrayHasKey('hex_apos', $options);
		$this->assertFalse(array_key_exists('hex_apos', $options) AND $options['hex_apos']);

		$this->assertArrayHasKey('hex_quot', $options);
		$this->assertTrue(array_key_exists('hex_quot', $options) AND $options['hex_quot']);

		$this->assertArrayHasKey('force_object', $options);
		$this->assertTrue(array_key_exists('force_object', $options) AND $options['force_object']);

		if (version_compare(PHP_VERSION, '5.3.3') >= 0)
		{
			$this->assertArrayHasKey('numeric_check', $options);
			$this->assertFalse(array_key_exists('numeric_check', $options) AND $options['numeric_check']);
		}

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$this->assertArrayHasKey('bigint_as_string', $options);
			$this->assertFalse(array_key_exists('bigint_as_string', $options) AND $options['bigint_as_string']);

			$this->assertArrayHasKey('pretty_print', $options);
			$this->assertFalse(array_key_exists('pretty_print', $options) AND $options['pretty_print']);

			$this->assertArrayHasKey('unescaped_slashed', $options);
			$this->assertFalse(array_key_exists('unescaped_slashed', $options) AND $options['unescaped_slashed']);

			$this->assertArrayHasKey('unescaped_unicode', $options);
			$this->assertFalse(array_key_exists('unescaped_unicode', $options) AND $options['unescaped_unicode']);
		}

	}

	public function testProcessOptions()
	{
		$options = array(
			'hex_quot' => true,
			'hex_tag' => false,
			'hex_amp' => true,
			'hex_apos' => false,
			'force_object' => true
		);

		if (version_compare(PHP_VERSION, '5.3.3') >= 0)
		{
			$options['numeric_check'] = true;
		}

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$options['bigint_as_string'] = false;
			$options['pretty_print'] = true;
			$options['unescaped_slashed'] = false;
			$options['unescaped_unicode'] = false;
		}

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

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$this->assertEquals(JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT, JsonOptions::processOptions($options, $options_map));
		}
		elseif (version_compare(PHP_VERSION, '5.3.3') >= 0)
		{
			$this->assertEquals(JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK, JsonOptions::processOptions($options, $options_map));
		}
		else {
			$this->assertEquals(JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT, JsonOptions::processOptions($options, $options_map));
		}
	}

}


?>