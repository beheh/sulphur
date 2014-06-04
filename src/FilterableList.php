<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class FilterableList implements \IteratorAggregate, \ArrayAccess, \Countable {

	private $elements;
	private $field;

	public function __construct($elements, $field) {
		$this->elements = $elements;
		$this->field = $field;
	}

	public function __call($name, $arguments) {
		array_unshift($arguments, $this->field);
		$result = array();
		foreach($this->elements as $element) {
			if(call_user_func_array(array($element, $name), $arguments)) {
				$result[] = $element;
			}
		}
		return new FilterableList($result, $this->field);
	}

	public function where($field) {
		return new FilterableList($this->elements, $field);
		//$this->field = $field;
	}

	public function getIterator() {
		return new \ArrayIterator($this->elements);
	}

	public function count() {
		return count($this->elements);
	}

	public function offsetSet($offset, $value) {
		if(is_null($offset)) {
			$this->elements[] = $value;
		} else {
			$this->elements[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->elements[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->elements[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->elements[$offset]) ? $this->elements[$offset] : null;
	}

}
