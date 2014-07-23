<?php namespace Hampel\Json;

class JsonTest extends \PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
		$this->assertEquals(json_encode($data), (new Json())->encode($data));
		$this->assertEquals(json_encode($data), Json::encode($data)); // static version
	}

	public function testEncodeWithOptions()
	{
		$data = array('<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9");

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), (new Json())->encode($data, $bitmask));
		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask)); // static version
	}

	public function testEncodeWithObject()
	{
		$data = array(array(1,2,3));

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), (new Json())->encode($data, $bitmask));
		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask)); // static version
	}

	public function testEncodeBroken()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error encoding JSON: Malformed UTF-8 characters, possibly incorrectly encoded');
		$error = (new Json())->encode(array(pack("H*" ,'c32e')));
	}

	public function testEncodeBrokenStatic()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error encoding JSON: Malformed UTF-8 characters, possibly incorrectly encoded');
		$error = Json::encode(array(pack("H*" ,'c32e')));
	}

	public function testDecode()
	{
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data), (new Json())->decode($data));
		$this->assertEquals(json_decode($data), Json::decode($data)); // static version

		$this->assertEquals(json_decode($data, true), (new Json())->decode($data, true));
		$this->assertEquals(json_decode($data, true), Json::decode($data, true)); // static version
	}

	public function testDecodeBrokenSyntaxError()
	{
		$this->setExpectedException('\Hampel\Json\JsonException', 'Error decoding JSON: Syntax error');
		$bad_json = "{ 'bar': 'baz' }";
		$error = (new Json())->decode($bad_json);
	}

	public function testDecodeBrokenSyntaxErrorStatic()
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

		$error = (new Json())->decode($json, true, 3);
	}

	public function testDecodeBrokenStackDepthStatic()
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

		$error = Json::decode($json, true, 3); // static version
	}
}

?>