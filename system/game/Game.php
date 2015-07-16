<?php
/**
* 	JIndie
*	@package JIndie
*	@category Game Components
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

require_once(GAME_PATH.'/Goal.php');
require_once(GAME_PATH.'/Score.php');
require_once(GAME_PATH.'/Menu.php');
require_once(GAME_PATH.'/IArtefact.php');

class Game {
	

	private $artifact;
	private $score;
	private $goal;
	private $scene;
	private $menu;

	private $instance;

	private function __contruct() {
		
	}

	public static function getInstance() {
		if ($this->instance == null) {
			$session = \Session::getInstance();
			$game = $session->getGame();
			
			if (!empty($game))
				$this->instance = $game;
			else
			
			$this->instance = new Game();
		}
		return $this->instance;
	}


	private function __destroy() {
		$session = \Session::getInstance();
		$session->saveGame($this);
	}

	public function setArtifact($artifact) {
		if ($artifact instanceof IArtefact)
			$this->artifact = $artifact;
		else
			throw new Exception();
	}

	public function getArtifact() {
		return $this->artifact
	}

	public function showHUD() {

	}




}