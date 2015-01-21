<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Section extends Filterable {

	/**
	 * @var array the child sections of this section
	 */
	protected $subsections = array();

	/**
	 * @var string the heading of this section
	 */
	protected $heading;

	public function __construct($heading) {
		$this->setHeading($heading);
		parent::__construct();
	}

	/**
	 * Sets the heading of this section.
	 * @param string $heading the new heading
	 */
	public function setHeading($heading) {
		$this->heading = $heading;
	}

	/**
	 * Returns the heading of this section.
	 * @return string the heading
	 */
	public function getHeading() {
		return $this->heading;
	}

	/**
	 * Adds the section as child section.
	 * @param Section $section the section to add
	 */
	public function addSubsection(Section $section) {
		$this->subsections[] = $section;
	}
	
	public function all($heading = null) {
		$sections = array();
		foreach($this->subsections as $section) {
			if($section->getHeading() === $heading || $heading === null) {
				$sections[] = $section;
			}
		}
		return $sections;
	}
	
	public function first($heading = null) {
		$sections = $this->all($heading);
		if(count($sections)) {
			return $sections[0];
		}
		return null;
	}

}
