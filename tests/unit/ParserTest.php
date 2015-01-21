<?php

namespace Sulphur;

class ParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Parser
	 */
	protected $parser;

	protected function setUp() {
		$this->parser = new Parser();
	}

	/**
	 * @covers Sulphur\Parser::parse
	 */
	public function testParse() {
		$response = $this->parser->parse(file_get_contents(__DIR__.'/../data/reference.ini'));
		$references = $response->all();
		$reference = $references[0];
		$this->assertEquals(12, $reference->Icon);
		$this->assertEquals('Minor Melee', $reference->Title);
		$this->assertEquals(true, $reference->IsNetworkGame);
		$this->assertEquals(123456, $reference->GameId);
		$this->assertNull($reference->Type);
	}

	/**
	 * @covers Sulphur\Parser::cast
	 */
	public function testCast() {
		$this->assertSame(true, Parser::cast('true'));
		$this->assertSame(true, Parser::cast('TRUE'));
		$this->assertSame(false, Parser::cast('false'));
		$this->assertSame(false, Parser::cast('FALSE'));
		$this->assertSame('String', Parser::cast('"String"'));
		$this->assertSame('true', Parser::cast('"true"'));
		$this->assertSame('TRUE', Parser::cast('"TRUE"'));
		$this->assertSame('false', Parser::cast('"false"'));
		$this->assertSame('FALSE', Parser::cast('"FALSE"'));
		$this->assertSame('42', Parser::cast('"42"'));
		$this->assertSame(42, Parser::cast('42'));
		$this->assertNotSame(42, Parser::cast('"42"'));
	}

}
