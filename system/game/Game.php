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

require_once(GAME_JI_PATH.'Goal.php');
require_once(GAME_JI_PATH.'Score.php');
require_once(GAME_JI_PATH.'Menu.php');
require_once(GAME_JI_PATH.'IArtefact.php');
require_once(SYSTEM_PATH.'libraries/Session.php');

class Game {
	

	private $artifact;
	private $score;
	private $goal;
	private $scene;
	private $menu;

	private static $instance;

	private function __construct() {
		$this->score = new Score;
		$this->goal = new Goal;
		$this->menu = new Menu;
	}

	public static function getInstance() {
		if (self::$instance == null) {
			$session = new \Session;
			$game = $session->loadGame();
			
			
			if (!is_null($game))
				self::$instance = $game;
			else {
				self::$instance = new Game();
			}
		}
		return self::$instance;
	}
	
	public function showHUD() {

	}

	////// Getters and Setters ///////

	public function setArtifact($artifact) {
		if ($artifact instanceof IArtefact)
			$this->artifact = $artifact;
		else
			throw new Exception();
	}

	public function getArtifact() {
		return $this->artifact;
	}

	public function setScore($score) {
		if (is_subclass_of($score, 'JIndie\Game\Score'))
			$this->score = $score;
		else
			throw new Exception();		
	}

	public function getScore() {
		return $this->score;
	}

	public function setGoal($goal) {
		if (is_subclass_of($goal, 'JIndie\Game\Goal'))
			$this->goal = $goal;
		else
			throw new Exception();		
	}

	public function getGoal() {
		return $this->goal;
	}

	public function setScene($scene) {
		if ($scene instanceof IScene)
			$this->scene = $scene;
		else
			throw new Exception();
	}

	public function getScene() {
		return $this->scene;
	}

	public function setMenu($menu) {
		if (is_subclass_of($menu, 'JIndie\Game\Menu'))
			$this->menu = $menu;
		else
			throw new Exception();		
	}

	public function getMenu() {
		return $this->menu;
	}
}