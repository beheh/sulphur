<?php

namespace Sulphur;

class SectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers Sulphur\Section::getHeading
	 */
	public function testGetHeading() {
		$section = new Section('Reference');
		$this->assertEquals('Reference', $section->getHeading());
		$section = new Section('reference');
		$this->assertNotEquals('Reference', $section->getHeading());
	}

}
