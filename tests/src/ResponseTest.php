<?php

namespace Sulphur;

class ResponseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Response
	 */
	protected $response;

	protected function setUp() {
		$factory = new ResponseFactory;
		$this->response = $factory->fromString(file_get_contents(__DIR__.'/data/reference.ini'));
	}

	/**
	 * @covers Sulphur\Response::parse
	 */
	public function testParse() {
		$references = $this->response->all();
		$reference = $references[0];
		$this->assertEquals(12, $reference->Icon);
		$this->assertEquals('Minor Melee', $reference->Title);
		$this->assertEquals(true, $reference->IsNetworkGame);
		$this->assertEquals(123456, $reference->GameId);
		$this->assertNull($reference->Type);
	}

	/**
	 * @covers Sulphur\Response::where
	 */
	public function testWhere() {
		$where = $this->response->where('foo');
		$this->assertInstanceOf('Sulphur\FilterableList', $where);
		$this->assertEquals(1, count($where));
	}

	/**
	 * @covers Sulphur\Response::all
	 */
	public function testAll() {
		$all = $this->response->all();
		$this->assertInstanceOf('Sulphur\FilterableList', $all);
		$this->assertEquals(1, count($all));
	}

	/**
	 * @covers Sulphur\Response::all
	 */
	public function testCast() {
		$this->assertSame(true, Response::cast('true'));
		$this->assertSame(true, Response::cast('TRUE'));
		$this->assertSame(false, Response::cast('false'));
		$this->assertSame(false, Response::cast('FALSE'));
		$this->assertSame('String', Response::cast('"String"'));
		$this->assertSame('true', Response::cast('"true"'));
		$this->assertSame('TRUE', Response::cast('"TRUE"'));
		$this->assertSame('false', Response::cast('"false"'));
		$this->assertSame('FALSE', Response::cast('"FALSE"'));
		$this->assertSame('42', Response::cast('"42"'));
		$this->assertSame(42, Response::cast('42'));
		$this->assertNotSame(42, Response::cast('"42"'));
	}

}
