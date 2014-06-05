<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class FilterableList implements \IteratorAggregate, \ArrayAccess, \Countable {

	protected $elements;
	protected $field;

	public function __construct($elements, $field) {
		$this->elements = $elements;
		$this->field = $field;
	}

	public function __call($name, $arguments) {
		// only defer filters if a field is defined
		if(!$this->field) {
			return;
		}
		// only if elements are remaining
		if(!empty($this->elements)) {
			// prepend field to filter arguments
			array_unshift($arguments, $this->field);
			$result = array();
			foreach($this->elements as $element) {
				// apply filter to all elements
				if(call_user_func_array(array($element, $name), $arguments)) {
					$result[] = $element;
				}
			}
		}
		// clone list with filtered elements
		return new FilterableList($result, $this->field);
	}

	/**
	 * Sets the field for the next filter.
	 * @param type $field the field to filter on
	 */
	public function where($field) {
		$this->field = $field;
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
