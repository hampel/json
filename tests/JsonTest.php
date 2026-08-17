<?php namespace Hampel\Json\Tests;

use Hampel\Json\Json;
use Hampel\Json\JsonException;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
	public function testEncode(): void
	{
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

		$this->assertEquals(json_encode($data), Json::encode($data));
	}

	public function testEncodeWithOptions(): void
	{
		$data = ['<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9"];

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeWithObject(): void
	{
		$data = [[1,2,3]];

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeBroken(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error encoding JSON: Malformed UTF-8 characters, possibly incorrectly encoded');

		Json::encode([pack("H*" ,'c32e')]);
	}

	public function testDecode(): void
	{
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data), Json::decode($data));

		$this->assertEquals(json_decode($data, true), Json::decode($data, true));

		$this->assertEquals(json_decode($data, true), Json::decode($data, Json::DECODE_ASSOC));
	}

	public function testDecodeNullLiteral(): void
	{
		// null is valid JSON and must decode to null, not throw - a legitimate
		// null result was previously indistinguishable from a failure
		$this->assertNull(Json::decode('null'));
	}

	public function testDecodeFalsyValues(): void
	{
		$this->assertFalse(Json::decode('false'));
		$this->assertSame(0, Json::decode('0'));
		$this->assertSame('', Json::decode('""'));
	}

	public function testDecodeBrokenSyntaxError(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error decoding JSON: Syntax error');

		$bad_json = "{ 'bar': 'baz' }";
		Json::decode($bad_json);
	}

	public function testEncodeNaN(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error encoding JSON: The value passed to json_encode() includes either NAN or INF');

		Json::encode(NAN);
	}

	public function testDecodeControlCharacter(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error decoding JSON: Control character error, possibly incorrectly encoded');

		// a raw control character is not legal inside a JSON string
		Json::decode("\"a\x01b\"");
	}

	public function testEncodeRecursion(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error encoding JSON: The object or array passed to json_encode() include recursive references and cannot be encoded');

		$data = [];
		$data['self'] = &$data;

		Json::encode($data);
	}

	public function testEncodeUnsupportedType(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error encoding JSON: A value of an unsupported type was given to json_encode(), such as a resource');

		$handle = fopen('php://memory', 'r');

		try
		{
			Json::encode($handle);
		}
		finally
		{
			if ($handle !== false) fclose($handle);
		}
	}

	public function testEncodeBrokenStackDepth(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error encoding JSON: The maximum stack depth has been exceeded');

		Json::encode([[['too deep']]], 0, 2);
	}

	public function testExceptionFallsBackToUnknownError(): void
	{
		// a code with no entry in JsonException::$messages
		$exception = new JsonException("Error decoding JSON:", 999);

		$this->assertSame('Error decoding JSON: Unknown Error', $exception->getMessage());
	}

	public function testExceptionChainsAnyThrowable(): void
	{
		$previous = new \Error("the underlying cause");

		$exception = new JsonException("Error decoding JSON:", JSON_ERROR_SYNTAX, $previous);

		$this->assertSame($previous, $exception->getPrevious());
	}

	public function testDecodeBrokenStackDepth(): void
	{
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Error decoding JSON: The maximum stack depth has been exceeded');

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

		Json::decode($json, true, 3);
	}
}
