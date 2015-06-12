<?php

namespace BehEh\Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class FilterableList implements \IteratorAggregate, \ArrayAccess, \Countable {

	protected $elements = array();
	protected $field = null;

	/**
	 * @param array $elements
	 * @param null|string $field
	 * @throws \InvalidArgumentException
	 */
	public function __construct($elements, $field) {
		if(!is_array($elements)) {
			throw new \InvalidArgumentException('expected array for parameter $elements (was given '.gettype($elements).')');
		}
		if(!is_string($field) && !is_null($field)) {
			throw new \InvalidArgumentException('expected string or null for parameter $field (was given '.gettype($field).')');
		}

		$this->elements = $elements;
		$this->field = $field;
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @return \Sulphur\FilterableList
	 * @throws \InvalidArgumentException
	 */
	public function __call($name, $arguments) {
		if(!is_string($name)) {
			throw new \InvalidArgumentException('expected string for parameter $name (was given '.gettype($name).')');
		}
		if(!is_array($arguments)) {
			throw new \InvalidArgumentException('expected array for parameter $arguments (was given '.gettype($arguments).')');
		}

		// only defer filters if a field is defined
		if($this->field === null) {
			return;
		}
		$result = array();
		// only if elements are remaining
		if(!empty($this->elements)) {
			// prepend field to filter arguments
			array_unshift($arguments, $this->field);
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
	 * @param string $field the field to filter on
	 * @return FilterableList
	 */
	public function where($field) {
		$this->field = $field;
		return $this;
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
