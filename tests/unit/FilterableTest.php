<?php

namespace BehEh\Sulphur;

class FilterableTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Filterable
	 */
	protected $filterable;

	protected function setUp() {
		$this->filterable = new Filterable(array(
			'Icon' => 12,
			'Title' => 'Minor Melee',
			'State' => 'Lobby'
		));
	}

	/**
	 * @covers Sulphur\Filterable::is
	 */
	public function testIs() {
		$this->assertTrue($this->filterable->is('Icon', 12));
		$this->assertTrue($this->filterable->is('Title', 'Minor Melee'));
		$this->assertFalse($this->filterable->is('Icon', 42));
		$this->assertFalse($this->filterable->is('Title', 'Clepal'));
	}

	/**
	 * @covers Sulphur\Filterable::isNot
	 */
	public function testIsNot() {
		$this->assertTrue($this->filterable->isNot('Icon', 42));
		$this->assertTrue($this->filterable->isNot('Title', 'Clepal'));
		$this->assertFalse($this->filterable->isNot('Icon', 12));
		$this->assertFalse($this->filterable->isNot('Title', 'Minor Melee'));
	}

	/**
	 * @covers Sulphur\Filterable::contains
	 */
	public function testContains() {
		$this->assertTrue($this->filterable->contains('Title', 'Minor'));
		$this->assertTrue($this->filterable->contains('Title', 'Minor', true));
		$this->assertTrue($this->filterable->contains('Title', 'minor', true));
		$this->assertFalse($this->filterable->contains('Title', 'minor'));
	}

	/**
	 * @covers Sulphur\Filterable::doesNotContain
	 */
	public function testDoesNotContain() {
		$this->assertTrue($this->filterable->doesNotContain('Title', 'minor'));
		$this->assertFalse($this->filterable->doesNotContain('Title', 'Minor'));
		$this->assertFalse($this->filterable->doesNotContain('Title', 'Minor', true));
		$this->assertFalse($this->filterable->doesNotContain('Title', 'minor', true));
	}

	/**
	 * @covers Sulphur\Filterable::matches
	 */
	public function testMatches() {
		$this->assertTrue($this->filterable->matches('Icon', '/^[0-9]+$/'));
		$this->assertTrue($this->filterable->matches('Title', '/Minor Melee/'));
		$this->assertTrue($this->filterable->matches('Title', '/^Minor.*$/'));
		$this->assertTrue($this->filterable->matches('Title', '/^minor.*$/i'));
		$this->assertTrue($this->filterable->matches('State', '/^.*$/'));
		$this->assertFalse($this->filterable->matches('Icon', '/[a-z]/'));
		$this->assertFalse($this->filterable->matches('Title', '/foo/'));
		$this->assertFalse($this->filterable->matches('State', '/lobby/'));
	}

	/**
	 * @covers Sulphur\Filterable::doesNotMatch
	 */
	public function testDoesNotMatch() {
		$this->assertTrue($this->filterable->doesNotMatch('Icon', '/[a-z]/'));
		$this->assertTrue($this->filterable->doesNotMatch('Title', '/foo/'));
		$this->assertTrue($this->filterable->doesNotMatch('State', '/lobby/'));
		$this->assertFalse($this->filterable->doesNotMatch('Icon', '/^[0-9]+$/'));
		$this->assertFalse($this->filterable->doesNotMatch('Title', '/Minor Melee/'));
		$this->assertFalse($this->filterable->doesNotMatch('Title', '/^Minor.*$/'));
		$this->assertFalse($this->filterable->doesNotMatch('Title', '/^minor.*$/i'));
		$this->assertFalse($this->filterable->doesNotMatch('State', '/^.*$/'));
	}

	/**
	 * @covers Sulphur\Filterable::exists
	 */
	public function testExists() {
		$this->assertTrue($this->filterable->exists('Icon'));
		$this->assertTrue($this->filterable->exists('Title'));
		$this->assertFalse($this->filterable->exists('icon'));
		$this->assertFalse($this->filterable->exists('title'));
		$this->assertFalse($this->filterable->exists('foo'));
		$this->assertFalse($this->filterable->exists('*'));
		$this->assertFalse($this->filterable->exists('.*'));
	}

	/**
	 * @covers Sulphur\Filterable::doesNotExist
	 */
	public function testDoesNotExist() {
		$this->assertTrue($this->filterable->doesNotExist('icon'));
		$this->assertTrue($this->filterable->doesNotExist('title'));
		$this->assertTrue($this->filterable->doesNotExist('foo'));
		$this->assertTrue($this->filterable->doesNotExist('*'));
		$this->assertTrue($this->filterable->doesNotExist('.*'));
		$this->assertFalse($this->filterable->doesNotExist('Icon'));
		$this->assertFalse($this->filterable->doesNotExist('Title'));
	}

	/**
	 * @covers Sulphur\Filterable::passes
	 */
	public function testPasses() {
		$this->assertTrue($this->filterable->passes('Title', function($field, $value) { return strlen($value) > 5; }));
		$this->assertTrue($this->filterable->passes('Title', function($field, $value) { return $field === 'Title'; }));
		$this->assertTrue($this->filterable->passes('Icon', function($field, $value) { return $value === 12; }));
		$this->assertTrue($this->filterable->passes('foo', function($field, $value) { return $value === null; }));
		$this->assertFalse($this->filterable->passes('Title', function($field, $value) { return strlen($value) <= 3; }));
		$this->assertFalse($this->filterable->passes('Title', function($field, $value) { return $field === 'State'; }));
		$this->assertFalse($this->filterable->passes('Icon', function($field, $value) { return $value === '12'; }));
		$this->assertFalse($this->filterable->passes('foo', function($field, $value) { return $value !== null; }));
	}

	/**
	 * @covers Sulphur\Filterable::doesNotPass
	 */
	public function testDoesNotPass() {
		$this->assertTrue($this->filterable->doesNotPass('Title', function($field, $value) { return strlen($value) <= 3; }));
		$this->assertTrue($this->filterable->doesNotPass('Title', function($field, $value) { return $field === 'State'; }));
		$this->assertTrue($this->filterable->doesNotPass('Icon', function($field, $value) { return $value === '12'; }));
		$this->assertTrue($this->filterable->doesNotPass('foo', function($field, $value) { return $value !== null; }));
		$this->assertFalse($this->filterable->doesNotPass('Title', function($field, $value) { return strlen($value) > 5; }));
		$this->assertFalse($this->filterable->doesNotPass('Title', function($field, $value) { return $field === 'Title'; }));
		$this->assertFalse($this->filterable->doesNotPass('Icon', function($field, $value) { return $value === 12; }));
		$this->assertFalse($this->filterable->doesNotPass('foo', function($field, $value) { return $value === null; }));
	}

	/**
	 * @covers Sulphur\Filterable::__get
	 */
	public function test__get() {
		$this->assertEquals(12, $this->filterable->Icon);
		$this->assertEquals('Minor Melee', $this->filterable->Title);
		$this->assertNull($this->filterable->title);
		$this->assertNull($this->filterable->foo);
	}

}
