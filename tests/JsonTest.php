<?php namespace Hampel\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

		$this->assertEquals(json_encode($data), Json::encode($data));
	}

	public function testEncodeWithOptions()
	{
		$data = ['<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9"];

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeWithObject()
	{
		$data = [[1,2,3]];

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeBroken()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error encoding JSON: Malformed UTF-8 characters, possibly incorrectly encoded');
		$error = Json::encode([pack("H*" ,'c32e')]);
	}

	public function testDecode()
	{
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data), Json::decode($data));

		$this->assertEquals(json_decode($data, true), Json::decode($data, true));

		$this->assertEquals(json_decode($data, true), Json::decode($data, Json::DECODE_ASSOC));
	}

	public function testDecodeBrokenSyntaxError()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error decoding JSON: Syntax error');
		$bad_json = "{ 'bar': 'baz' }";
		$error = Json::decode($bad_json);
	}

	public function testEncodeNaN()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error encoding JSON: The value passed to json_encode() includes either NAN or INF');
		$error = Json::encode(NAN);
	}

	public function testDecodeBrokenStackDepth()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error decoding JSON: The maximum stack depth has been exceeded');

		$json = json_encode(
			[
				1 => [
					'English' => [
						'One',
						'January'
					],
					'French' => [
						'Une',
						'Janvier'
					]
				]
			]
		);

		$error = Json::decode($json, true, 3);
	}
}

?>