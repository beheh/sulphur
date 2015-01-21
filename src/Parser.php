<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Parser {

	/**
	 * @var array the parser depth stack
	 */
	protected $stack = array();
	
	/**
	 * Parses the passed response and passes the key-value pairs to references.
	 * @param string $string the string to parse
	 * @return Response the response
	 */
	public function parse($string) {

		$response = new Response();

		// normalize line endings
		$filtered = preg_replace("/(\n|\r){2,}/", PHP_EOL, $string);

		$lines = explode(PHP_EOL, $filtered);

		foreach($lines as $line) {
			$matches = array();
			if(preg_match('/^(\s*)\[(.*)\]$/', $line, $matches)) {
				// entering new section
				$depth = strlen($matches[1]);
				if(isset($this->stack[$depth])) {
					// found previous section at depth of current (new) section
					// save existing section
					$this->completeSection($response, $depth);
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
			$this->completeSection($response, $depth);
		}
		
		return $response;
	}
	
	protected function completeSection(Response $response, $depth) {
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
			$response->addSection($section);
		}
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
