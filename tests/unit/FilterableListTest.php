<?php

namespace Sulphur;

class FilterableListTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var FilterableList
	 */
	protected $list;

	protected function setUp() {
		$this->list = new FilterableList(
				array(
					new Filterable(array('Title' => 'Minor Melee', 'League' => false)),
					new Filterable(array('Title' => 'Minor Melee', 'League' => true)),
					new Filterable(array('Title' => 'Clepal'))
				), null);
	}

	/**
	 * @covers Sulphur\FilterableList::__call
	 */
	public function test__call() {
		$references = $this->list->where('Title')->is('Minor Melee');
		$this->assertEquals('Minor Melee', $references[0]->Title);
	}

	/**
	 * @depends test__call
	 * @covers Sulphur\FilterableList::where
	 */
	public function testWhere() {
		$this->assertEquals(2, count($this->list->where('Title')->is('Minor Melee')));
		$this->assertEquals(1, count($this->list->where('Title')->is('Minor Melee')->where('League')->is(true)));
		$this->assertEquals(0, count($this->list->where('State')->is('Running')->where('League')->exists()));
	}

	/**
	 * @covers Sulphur\FilterableList::getIterator
	 */
	public function testGetIterator() {
		$this->assertInstanceOf('\Traversable', $this->list->getIterator());
	}

	/**
	 * @covers Sulphur\FilterableList::count
	 */
	public function testCount() {
		$this->assertInternalType('int', $this->list->count());
	}

	/**
	 * @covers Sulphur\FilterableList::offsetExists
	 */
	public function testOffsetExists() {
		$this->assertEquals(true, $this->list->offsetExists(0));
		$this->assertEquals(false, $this->list->offsetExists(3));
	}

	/**
	 * @covers Sulphur\FilterableList::offsetGet
	 */
	public function testOffsetGet() {
		$this->assertInstanceOf('Sulphur\Filterable', $this->list->offsetGet(0));
	}

}