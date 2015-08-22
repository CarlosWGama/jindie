<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Components of Game 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

require_once(GAME_JI_PATH.'Goal.php');
require_once(GAME_JI_PATH.'Score.php');
require_once(GAME_JI_PATH.'Menu.php');
require_once(GAME_JI_PATH.'Artefact.php');
require_once(GAME_JI_PATH.'Component.php');
require_once(SYSTEM_PATH.'libraries/Session.php');

class Game {
	
	/**
	* @access protected
	* @var IArtefact
	*/
	protected $artefact;

	/**
	* @access protected
	* @var Score
	*/
	protected $score;

	/**
	* @access protected
	* @var Goal
	*/
	protected $goal;

	/**
	* Exibe um conteúdo visto pelo usuário
	* @access protected
	* @var IScene
	*/
	protected $scene;

	/**
	* @access protected
	* @var Menu
	*/
	protected $menu;

	/**
	* @access protected
	* @var this
	*/
	protected static $instance;

	/**
	* @access protected
	*/
	protected function __construct() {
		$this->score 	= new Score;
		$this->goal 	= new Goal;
		$this->menu 	= new Menu;
		$this->artefact = new Artefact;
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

	/******* Getters and Setters ********/
	/**
	* @access public
	* @param IArtefact $artefact
	*/
	public function setArtefact($artefact) {
		if ($artefact == null) {
			\Log::message(\Language::getMessage('log', 'debug_game_artefact_null'), 2);
			$this->artefact = null;
		}
		elseif ($artefact instanceof IArtefact) {
			\Log::message(\Language::getMessage('log', 'debug_game_artefact'), 2);
			$this->artefact = $artefact;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_artefact');
			\Log::message($msg, 2);
			throw new \Exception($msg, 16);
		}
	}

	/**
	* @access public
	* @return IArtefact 
	*/
	public function getArtefact() {
		return $this->artefact;
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
			$this->scene = $scene;	
			\Log::message(\Language::getMessage('log', 'debug_game_scene'), 2); 
		} else {
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
	* Exibe os conteúdos da Scene
	* @param bool $returnHTML
	* @return string
	*/
	public function showScene($returnHTML = false) {
		if (is_null($this->scene)) {
			$msg = \Language::getMessage('error', 'game_scene_null');
			\Log::message($msg, 2);
			throw new \Exception($msg, 35);	
		}

		try {
			\Log::message(\Language::getMessage('log', 'debug_game_scene_check'), 2); 		
			$this->scene->check();

			if ($returnHTML) {
				ob_start();
				$this->scene->showScene();
				return ob_get_clean();
			}
			else
				$this->scene->showScene();
		} catch (Exception $ex) {
				\Log::message("GAME/Scene: " . $ex->getMessage(), 2);  
				throw new Exception($ex->getMessage(), $ex->getCode());
		}
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
	* @return Menu
	*/
	public function getMenu() {
		return $this->menu;
	}
}