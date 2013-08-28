<?php namespace Hampel\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		$this->assertEquals(json_encode($data), Json::encode($data));
	}

	public function testEncodeWithOptions()
	{
		$data = array('<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9");

		$options = array(
			'hex_tag' => true,
			'hex_amp' => true,
			'hex_apos' => true,
			'hex_quot' => true,
		);

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $options));
	}

	public function testEncodeWithObject()
	{
		$data = array(array(1,2,3));

		$options = array(
			'force_object' => true,
		);

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $options));
	}

	public function testEncodeBroken()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error encoding JSON: Malformed UTF-8 characters, possibly incorrectly encoded');
		$error = Json::encode(array(pack("H*" ,'c32e')));
	}

	public function testDecode()
	{
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data), Json::decode($data));
		$this->assertEquals(json_decode($data, true), Json::decode($data, true));
	}

	public function testDecodeBrokenSyntaxError()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error decoding JSON: Syntax error');
		$bad_json = "{ 'bar': 'baz' }";
		$error = Json::decode($bad_json);
	}

	public function testDecodeBrokenStackDepth()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error decoding JSON: The maximum stack depth has been exceeded');

		$json = json_encode(
			array(
				1 => array(
					'English' => array(
						'One',
						'January'
					),
					'French' => array(
						'Une',
						'Janvier'
					)
				)
			)
		);

		$error = Json::decode($json, true, 3);
	}
}

?>