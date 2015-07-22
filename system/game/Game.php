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
			
			if (!is_null($game)) {
				self::$instance = $game;
				\Log::message(\Language::getMessage('log', 'debug_game_load'), 2);
			}
			else {
				$class = get_called_class();
				self::$instance = new $class();
				\Log::message(\Language::getMessage('log', 'debug_game_new', array('class_game' => $class)), 2);
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
		Log::message(\Language::getMessage('log', 'debug_game_hud', array('return_html' => ($returnHTML ? "TRUE" : "FALSE"))), 2);
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
		if ($artifact instanceof IArtefact) {
			Log::message(\Language::getMessage('log', 'debug_game_artifact'), 2);
			$this->artifact = $artifact;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_artifact');
			\Log::message($msg, 2);
			throw new Exception($msg, 16);
		}
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
		if (is_subclass_of($score, 'JIndie\Game\Score') || is_a($score, 'JIndie\Game\Score')) {
			\Log::message(\Language::getMessage('log', 'debug_game_score'), 2); 
			$this->score = $score;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_score');
			\Log::message($msg, 2);
			throw new Exception($msg, 17);
		}
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
		if (is_subclass_of($goal, 'JIndie\Game\Goal') || is_a($goal, 'JIndie\Game\Goal')) {
			\Log::message(Language::getMessage('log', 'debug_game_goal'), 2); 
			$this->goal = $goal;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_goal');
			\Log::message($msg, 2);
			throw new Exception($msg, 18);	
		}
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
				\Log::message(\Language::getMessage('log', 'debug_game_scene_check'), 2); 
				$scene->check();
				$this->scene = $scene;	
				\Log::message(\Language::getMessage('log', 'debug_game_scene'), 2); 
			} catch (Exception $ex) {
				\Log::message("GAME/Scene: " . $ex->getMessage(), 2);  
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_scene');
			\Log::message($msg, 2);
			throw new Exception($msg, 19);	
		}
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
		if (is_subclass_of($menu, 'JIndie\Game\Menu') || is_a($menu, 'JIndie\Game\Menu')) {
			\Log::message(\Language::getMessage('log', 'debug_game_menu'), 2); 
			$this->menu = $menu;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_menu');
			\Log::message($msg, 2);
			throw new Exception($msg, 20);	
		}
	}

	/**
	* @access public
	* @param Menu
	*/
	public function getMenu() {
		return $this->menu;
	}
}