<?php


class SceneMap extends Controller {
	
	public function __construct() {
		parent::__construct();
		$this->loadHelper('url');

		/*
		if (!$code = $this->input->post('code')) {

			$sceneMap = $this->getGameComponent('sceneMap');
			$sceneMap->getMap()->setMapSize(10, 10);

			

			//Criando o Mapa
			//
			$default = array(
				'field' 	=> 'http://localhost/map/fields/ground/grass.jpg',
				'object'	=> '',
				'url'		=> '',
			);
			$sceneMap->getMap()->setDefaultTile($default);

			//Objetos
			$char1 = array(
				'field' 	=> 'http://localhost/map/fields/ground/grass.jpg',
				'object'	=> 'http://localhost/map/objects/persons/person4.png',
				'url'		=> site_url('sceneMap/char1'),
			);

			$sceneMap->getMap()->addTileAtPosition(array(2,2), $char1);

			//
			$char2 = array(
				'field' 	=> 'http://localhost/map/fields/ground/grass.jpg',
				'object'	=> 'http://localhost/map/objects/persons/person6.png',
				'url'		=> site_url('sceneMap/char2'),
			);

			$sceneMap->getMap()->addTileAtPosition(array(9,2), $char2);


			$this->game->setScene($sceneMap);
		}
		*/
	}

	public function index() {
		//$this->game->getScene()->setPlayer('http://localhost/map/objects/persons/person19.png', array(5, 9));
		//$this->game->getScene()->setUrlCode(site_url('sceneMap/code'));
		//$this->game->getScene()->isCodeNavegation();
		$this->game->showScene();
	}

	public function pointAndClick() {
		$this->game->getScene()->isPACNavegation();
		$this->game->showScene();
	}

	public function code() {
		try {
			$code = $this->input->post('code');	
			echo $this->game->getScene()->runScript($code);
		} catch (Exception $ex) {
			Log::message($ex->getMessage(), 3);
		}
		
		
	
	}
	
}