<?php

class Artifact extends Controller {


	public function index() {
		
		$datas		= array(
			'moveUp' => array(
				'field' 	=> 'aaaaa',
				'object'	=> 'bbbbb',
			),

			'moveLeft' => array(
				'field' 	=> 'cccc',
				'object'	=> 'dddd',
				'url'		=> 'eeee'
			),
		);

		foreach ($datas as $code => $data) {
			$tile = $this->getGameComponent('Tile');
			$tile->setTile($data);
			$tile->setExtra('command', $code);
			//$this->game->getArtefact()->addComponent($tile);
		}

		print_r($this->game->getArtefact()->getComponents());

	}	
}