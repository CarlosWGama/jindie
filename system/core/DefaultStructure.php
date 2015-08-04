<?php
/**
* 	JIndie
*	@package JIndie
*	@category core
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

/**
* @abstract
*/
abstract class JI_DefaultStructure {
	/**
	* @access private
	* @var Loader
	*/ 
	private $loader = null;

	/**
	* Objeto que armazena as ações e lógica do jogo
	* @access protected
	* @var Game
	*/
	protected $game = null;

	public function __construct() {
		//Loader
		$this->loader = new Loader();

		//Game
		if (file_exists(GAME_PATH.'/Game.php')) {
		 	require_once(GAME_PATH.'/Game.php');
		 	$this->game = Game::getInstance();

		 	if  (!is_subclass_of($this->game, "JIndie\Game\Game"))
		 		$this->game = null;
		}

		if ($this->game == null) {
			require_once(GAME_JI_PATH.'/Game.php');
			$this->game = JIndie\Game\Game::getInstance();
		}

	}

	/**
	* carrega os models
	* @access protected
	* @param string $model
	* @param string $nameVar
	* @return bool
	*/
	protected function loadModel($model, $nameVar = '') {
		if (empty($nameVar) ||!is_string($nameVar)) 
			$nameVar = $model; 

		try {
			Log::message(Language::getMessage('log', 'debug_loader_model_try', array('model' => $model, 'class' => get_class())), 2);
			
			$this->{$nameVar} = $this->loader->model($model);
			
			Log::message(Language::getMessage('log', 'debug_loader_model_load', array('model' => $model, 'model_name' => $nameVar)), 2);

			return true;	
		} catch (Exception $ex) {
			Log::message(Language::getMessage('log', 'debug_loader_model_fail', array('model' => $model)), 2);
			
			JI_ERRORS::generalError($ex->getMessage());
			return false;
		}
	}

	/**
	* carrega as bibliotecas
	* @access protected
	* @param string $library
	* @param string $nameVar
	* @return bool
	*/
	protected function loadLibrary($library, $nameVar = '') {
		if (empty($nameVar) ||!is_string($nameVar)) 
			$nameVar = $library; 
		
		try {
			Log::message(Language::getMessage('log', 'debug_loader_library_try', array('library' => $library, 'class' => get_class($this))), 2);
			
			$this->{$nameVar} = $this->loader->library($library);

			Log::message(Language::getMessage('log', 'debug_loader_library_load', array('library' => $library, 'library_name' => $nameVar)), 2);

			return true;	
		} catch (Exception $ex) {
			Log::message(Language::getMessage('log', 'debug_loader_library_fail', array('library' => $library)), 2);

			JI_ERRORS::generalError($ex->getMessage());
			return false;
		}
	}

	/**
	* carrega o banco de dados
	* @access protected
	* @param string $name
	* @return void
	*/
	protected function loadDatabase($name = null) {
		require_once(SYSTEM_PATH.'/libraries/Database.php');
				
		//Default
		if ($name == null) 
			$name = 'db';

		Log::message(Language::getMessage('log', 'debug_database_new_instance'), 2);
		$this->{$name} = JI_Database::getInstance();	
	}

	/**
	* Recupera algum componente do jogo na pasta Game
	* @param string $component
	* @return object
	*/
	protected function getGameComponent($component) {
		$component = ucfirst($component);
		if (file_exists(GAME_PATH.$component.'.php')) {
			require_once(GAME_PATH.$component.'.php');
			return new $component;
		} else if (file_exists(GAME_JI_PATH.$component.'.php')) {
			require_once(GAME_JI_PATH.$component.'.php');
			$component = "JIndie\Game\\" . $component;
			return new $component;
		}
		return null;
	}

	/**
	* Carrega algum helper
	* @param string $helper
	*/
	protected function loadHelper($helper) {
		if (file_exists(HELPER_PATH.$helper.'.php'))
			require_once(HELPER_PATH.$helper.'.php');
		else
			require_once(HELPER_JI_PATH.$helper.'.php');
	}

	/**
	* Salva o Status atual da class Game, para novas requisições
	*/
	public function __destruct() {
		$session = new Session;
		$session->saveGame($this->game);
		Log::message(Language::getMessage('log', 'debug_game_save'), 2);
	}
}