<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Filterable {

	private $fields;

	public function __construct($fields) {
		$this->fields = $fields;
	}

	public function is($field, $value) {
		if(isset($this->fields[$field])) {
			return $this->fields[$field] == $value;
		}
		return false;
	}

	public function isNot($field, $value) {
		return !$this->is($field, $value);
	}

	public function contains($field, $needle, $ignore_case = false) {
		if(isset($this->fields[$field])) {
			if($ignore_case) {
				return stripos($this->fields[$field], $needle) !== false;
			}
			return strpos($this->fields[$field], $needle) !== false;
		}
		return false;
	}

	public function doesNotContain($field, $needle, $ignore_case = false) {
		return !$this->contains($field, $value);
	}

	public function matches($field, $expression) {
		if(isset($this->fields[$field])) {
			return preg_match($expression, $this->fields[$field]);
		}
		return false;
	}

	public function doesNotMatch($field, $expression) {
		return !$this->matches($field, $expression);
	}

	public function exists($field) {
		return isset($this->fields[$field]);
	}

	public function doesNotExist($field) {
		return !$this->exists($field);
	}

	public function __get($name) {
		if(isset($this->fields[$name])) {
			return $this->fields[$name];
		}
	}

}
