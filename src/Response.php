<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Response {

	/**
	 * @var array the sections of this response
	 */
	protected $sections = array();

	/**
	 * Add a section reference either to the root or parent section depending on the depth
	 * @param Section $section
	 */
	public function addSection(Section $section) {
		$this->sections[] = $section;
	}

	/**
	 * Sets the field for the first filter.
	 * @param string $field the field to filter
	 * @return FilterableList
	 */
	public function where($field, $section = 'Reference') {
		// default to reference
		if($section === null) {
			$section = 'Reference';
		}
		// array_values reindexes the array here, otherwise we have gaps from non-matching array keys
		return new FilterableList(array_values(array_filter($this->sections, function($val) use ($section) {
			// only return matching root sections
			return $val->getHeading() == $section;
		})), $field);
	}

	public function first($heading = 'Reference') {
		$references = $this->all($heading);
		if(count($references)) {
			return $references[0];
		}
		return null;
	}

	/**
	 * Returns all references.
	 * @return FilterableList
	 */
	public function all($heading = null) {
		return $this->where(null, $heading);
	}

}
