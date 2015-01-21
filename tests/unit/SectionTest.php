<?php

namespace Sulphur;

class SectionTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var Section
	 */
	protected $section;
	protected $shark;
	protected $wipf1;
	protected $wipf2;

	protected function setUp() {
		$this->section = new Section('Section');
		$this->shark = new Section('Shark');
		$this->section->addSubsection($this->shark);
		$this->wipf1 = new Section('Wipf');
		$this->section->addSubsection($this->wipf1);
		$this->wipf2 = new Section('Wipf');
		$this->section->addSubsection($this->wipf2);
	}
	
	public function testFirst() {
		$this->assertSame($this->wipf1, $this->section->first('Wipf'));
		$this->assertSame($this->shark, $this->section->first());
		$this->assertSame(array($this->wipf1, $this->wipf2), $this->section->all('Wipf'));
		$this->assertSame(array($this->shark, $this->wipf1, $this->wipf2), $this->section->all());
		$this->assertNull($this->section->first('Clonk'));
		$this->assertEmpty($this->section->all('Clonk'));
	}
}