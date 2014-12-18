<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Response {

	protected $sections = array();

	public function __construct($response) {
		$this->parse($response);
	}

	protected $stack = array();

	/**
	 * Parses the passed response and passes the key-value pairs to references.
	 * @param string $response the response to parse
	 */
	protected function parse($response) {

		// normalize line endings
		$response = str_replace(array("\r\n", "\r"), "\n", $response);
		$response = preg_replace("/\n{2,}/", "\n", $response);

		$lines = explode("\n", $response);

		foreach($lines as $line) {
			if(!$line) {
				continue;
			}
			$matches = array();
			if(preg_match('/^(\s*)\[(.*)\]$/', $line, $matches)) {
				$depth = strlen($matches[1]);
				if(isset($this->stack[$depth])) {
					// found previous section at depth of current (new) section
					// save existing section
					$this->addSection($depth);
				}
				$this->stack[$depth] = new Section($matches[2]);
			} else if(preg_match('/^(\s*)(.*)=(.*)$/', $line, $matches)) {
				$depth = strlen($matches[1]);
				if(isset($this->stack[$depth])) {
					$section = $this->stack[$depth];
					$section->__set($matches[2], self::cast($matches[3]));
				}
			}
		}
		// children have to found before parents
		krsort($this->stack);
		// merge remaining children into parents
		foreach($this->stack as $depth => $section) {
			$this->addSection($depth);
		}
	}

	/**
	 * Add a section reference either to the root or parent section depending on the depth
	 * @param int $depth
	 */
	protected function addSection($depth) {
		$section = $this->stack[$depth];
		// find parent
		$found = false;
		for($i = $depth - 1; $i >= 0; $i--) {
			if(isset($this->stack[$i])) {
				$this->stack[$i]->addSubsection($section);
				$found = true;
				break;
			}
		}
		if(!$found) {
			// root sections
			$this->sections[] = $section;
		}
	}

	/**
	 * Sets the field for the first filter.
	 * @param string $field the field to filter
	 * @return FilterableList
	 */
	public function where($field, $section = 'Reference') {
		// array_values reindexes the array here, otherwise we have gaps from non-matching array keys
		return new FilterableList(array_values(array_filter($this->sections, function($val) use ($section) {
							// only return matching root sections
							return $val->getHeading() == $section;
						})), $field);
	}

	/**
	 * Returns all references.
	 * @return FilterableList
	 */
	public function all() {
		return $this->where(null);
	}

	/**
	 * Casts the value to the correct datatype.
	 * @param string $value the value to cast
	 * @return any
	 */
	public static function cast($value) {
		$matches = array();
		if(strtolower($value) === 'true') {
			$value = (bool) true;
		} else if(strtolower($value) === 'false') {
			$value = (bool) false;
		} else if(is_numeric($value)) {
			$value = (int) $value;
		} else if(preg_match('/^"(.*)"$/', $value, $matches)) {
			$value = $matches[1];
		}
		return $value;
	}

}
