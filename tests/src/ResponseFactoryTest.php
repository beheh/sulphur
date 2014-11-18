<?php

namespace Sulphur;

class ResponseFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ResponseFactory
	 */
	protected $factory;

	protected function setUp() {
		$this->factory = new ResponseFactory;
	}

	/**
	 * @covers Sulphur\ResponseFactory::fromString
	 */
	public function testFromString() {
		$string = file_get_contents(__DIR__.'/data/reference.ini');
		$result = $this->factory->fromString($string);
		$this->assertInstanceOf('Sulphur\Response', $result);
	}

}
