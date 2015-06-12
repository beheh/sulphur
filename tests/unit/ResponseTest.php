<?php

namespace BehEh\Sulphur;

class ResponseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Response
	 */
	protected $response;

	protected function setUp() {
		$this->response = new Response();
	}

	/**
	 * @covers BehEh\Sulphur\Response::where
	 */
	public function testWhere() {
		$where = $this->response->where('foo');
		$this->assertInstanceOf('BehEh\Sulphur\FilterableList', $where);
		$this->assertEquals(0, count($where));
	}

	/**
	 * @covers BehEh\Sulphur\Response::all
	 */
	public function testAll() {
		$all = $this->response->all();
		$this->assertInstanceOf('BehEh\Sulphur\FilterableList', $all);
		$this->assertEquals(0, count($all));
	}

}
