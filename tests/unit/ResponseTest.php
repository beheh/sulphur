<?php

namespace Sulphur;

class ResponseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Response
	 */
	protected $response;

	protected function setUp() {
		$this->response = new Response();
	}

	/**
	 * @covers Sulphur\Response::where
	 */
	public function testWhere() {
		$where = $this->response->where('foo');
		$this->assertInstanceOf('Sulphur\FilterableList', $where);
		$this->assertEquals(0, count($where));
	}

	/**
	 * @covers Sulphur\Response::all
	 */
	public function testAll() {
		$all = $this->response->all();
		$this->assertInstanceOf('Sulphur\FilterableList', $all);
		$this->assertEquals(0, count($all));
	}

}
