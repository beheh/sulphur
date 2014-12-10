<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Response {

	protected $references = array();

	public function __construct($response) {
		$this->parse($response);
	}

	/**
	 * Parses the passed response and passes the key-value pairs to references.
	 * @param type $response the response to parse
	 */
	protected function parse($response) {

		// normalize line endings
		$response = str_replace(array("\r\n", "\r"), "\n", $response);
		$response = preg_replace("/\n{2,}/", "\n", $response);

		$lines = explode("\n", $response);

		$reference = null;
		$heading = true;
		foreach($lines as $line) {
			if(!$line) {
				continue;
			}
			$matches = array();
			if(preg_match('/^(\s)*\[(.*)\]$/', $line, $matches)) {
				$depth = strlen($matches[1]);
				// heading, like "[Reference]"
				switch($matches[2]) {
					case 'Reference':
						$heading = false;
						if(!empty($reference)) {
							$this->addReference($reference);
						}
						$reference = array();
						break;
				}
			} else if(preg_match('/^(.*)=(.*)$/', $line, $matches)) {
				// key-value pair, like "State=Lobby"
				if(!$heading) {
					$reference[trim($matches[1])] = $matches[2];
				}
			}
		}
		if(!empty($reference) && $reference !== null) {
			$this->addReference($reference);
		}
	}

	/**
	 * Adds a new Filterable to the references array.
	 * @param array $fields the fields to build the Filterable from
	 */
	protected function addReference($fields) {
		$this->references[] = new Filterable($fields);
	}

	/**
	 * Sets the field for the first filter.
	 * @param string $field the field to filter on
	 * @return FilterableList
	 */
	public function where($field) {
		return new FilterableList($this->references, $field);
	}

	/**
	 * Returns all references.
	 * @return FilterableList
	 */
	public function all() {
		return $this->where(null);
	}

}
