<?php

namespace Sulphur;

class ExampleTest extends \PHPUnit_Framework_TestCase {

	private $response;

	protected function setUp() {
		$parser = new Parser();
		$this->response = $parser->parse(file_get_contents(__DIR__.'/../data/reference.ini'));
	}

	public function testState() {
		$this->assertEquals(1, count($this->response->where('State')->is('Lobby')));
	}

	public function testTitle() {
		$references = $this->response->where('Title')->contains('melee', true);
		$this->assertEquals('Minor Melee', $references[0]->Title);
	}

	public function testChaining() {
		$references = $this->response->where('State')->is('Lobby')
						->where('IsNetworkGame')->is(true)
						->where('League')->doesNotExist();
		$this->assertEquals(1, count($references));
	}

	public function testClosure() {
		$this->assertEquals(1, count($this->response->where('Game')->passes(function($field, $value) {
							return $value === 'Clonk Rage';
						})));
	}

	public function testSubsections() {
		$resources = array();
		foreach($this->response->first('Reference')->all('Resource') as $resource) {
			$resources[] = $resource->Filename;
		}
		$this->assertEquals(array('Objects.c4d', 'Melees.c4f', 'System.c4g', 'Material.c4g'), $resources);
	}

	public function testMultipleSubsections() {
		$players = array();
		foreach($this->response->first('Reference')->first('PlayerInfos')->all('Client') as $client) {
			foreach($client->all('Player') as $player) {
				$players[] = $player->Name;
			}
		}
		$this->assertEquals(array('<~MW~> B_E'), $players);
	}

}
