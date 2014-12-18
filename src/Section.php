<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class Section extends Filterable {

	/**
	 * @var string
	 */
	protected $heading;

	public function __construct($heading) {
		$this->heading = $heading;
		parent::__construct();
	}

	/**
	 *
	 * @return string
	 */
	public function getHeading() {
		return $this->heading;
	}

	protected $subsections = array();

	public function addSubsection(Section $section) {
		$this->subsections[] = $section;
	}

}