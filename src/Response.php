<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Response {

	private $references = array();

	public function __construct($response) {

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
			if(preg_match('/^\[(.*)\]$/', $line, $matches)) {
				switch($matches[1]) {
					case 'Reference':
						$heading = false;
						if($reference) {
							$this->addReference($reference);
						}
						$reference = array();
						break;
				}
			} else {
				if(preg_match('/^(.*)=(.*)$/', $line, $matches)) {
					if(!$heading) {
						$reference[$matches[1]] = $matches[2];
					}
				}
			}
		}
		if($reference) {
			$this->addReference($reference);
		}
	}

	private function addReference($fields) {
		$this->references[] = new Filterable($fields);
	}

	public function where($field) {
		return new FilterableList($this->references, $field);
	}
	
	public function all() {
		return $this->where(null);
	}

}
