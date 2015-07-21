<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Components 
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
	
	/**
	* @access private
	* @var IArtefact
	*/
	private $artifact;

	/**
	* @access private
	* @var Score
	*/
	private $score;

	/**
	* @access private
	* @var Goal
	*/
	private $goal;

	/**
	* Exibe um conteúdo visto pelo usuário
	* @access private
	* @var IScene
	*/
	private $scene;

	/**
	* @access private
	* @var Menu
	*/
	private $menu;

	/**
	* @access private
	* @var this
	*/
	private static $instance;

	/**
	* @access private
	*/
	private function __construct() {
		$this->score = new Score;
		$this->goal = new Goal;
		$this->menu = new Menu;
	}

	/**
	* @access public
	*/
	public static function getInstance() {
		if (self::$instance == null) {
			$session = new \Session;
			$game = $session->loadGame();
			
			if (!is_null($game))
				self::$instance = $game;
			else {
				$class = get_called_class();
				self::$instance = new $class();
			}
		}
		return self::$instance;
	}
	
	/**
	* Exibe uma tela com dados do jogo e personagem para o jogador como vida/erros/...
	* @access public
	* @param bool $returnHTML
	* @return string   | Apenas se $returnHTML == true
	*/
	public function showHUD($returnHTML = false) {
		
		if ($returnHTML == true) {
			ob_start();
			include(VIEWS_PATH.'game/hud.php');
			$html = ob_get_clean();
			return $html;
		} else {
			include(VIEWS_PATH.'game/hud.php');
		}
	}

	////// Getters and Setters ///////
	/**
	* @access public
	* @param IArtifact $artifact
	*/
	public function setArtifact($artifact) {
		if ($artifact instanceof IArtefact)
			$this->artifact = $artifact;
		else
			throw new Exception();
	}

	/**
	* @access public
	* @return IArtifact 
	*/
	public function getArtifact() {
		return $this->artifact;
	}

	/**
	* @access public
	* @param Score $score
	*/
	public function setScore($score) {
		if (is_subclass_of($score, 'JIndie\Game\Score'))
			$this->score = $score;
		else
			throw new Exception();		
	}

	/**
	* @access public
	* @return Score
	*/
	public function getScore() {
		return $this->score;
	}

	/**
	* @access public
	* @param Goal $goal
	*/
	public function setGoal($goal) {
		if (is_subclass_of($goal, 'JIndie\Game\Goal'))
			$this->goal = $goal;
		else
			throw new Exception();		
	}

	/**
	* @access public
	* @return Goal
	*/
	public function getGoal() {
		return $this->goal;
	}

	/**
	* @access public
	* @param IScene $scene
	*/
	public function setScene($scene) {
		if ($scene instanceof IScene) {
			try {
				$scene->check();
				$this->scene = $scene;	
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
		else
			throw new Exception();
	}

	/**
	* @access public
	* @return IScene
	*/
	public function getScene() {
		return $this->scene;
	}

	/**
	* @access public
	* @param Menu $menu
	*/
	public function setMenu($menu) {
		if (is_subclass_of($menu, 'JIndie\Game\Menu'))
			$this->menu = $menu;
		else
			throw new Exception();		
	}

	/**
	* @access public
	* @param Menu
	*/
	public function getMenu() {
		return $this->menu;
	}
}