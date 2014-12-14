<?php

if(is_dir(__DIR__.'/../src/')) {
	set_include_path(__DIR__.'/../src/'.PATH_SEPARATOR.get_include_path());
}

require_once 'Filterable.php';
require_once 'FilterableList.php';
require_once 'Response.php';
require_once 'ResponseFactory.php';
require_once 'Section.php';