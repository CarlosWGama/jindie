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

final class View {

	/**
	* carrega uma única view
	* @param string $file
	* @param array $args
	*/
	public function render($file, $args = array()) {
		$game = $this->getGame();
		
		if (!empty($args))
			extract($args);
		if (file_exists(VIEWS_PATH.$file.'.php'))
			require(VIEWS_PATH.$file.'.php');
	}

	/**
	* carrega uma única view dentro de um template (modelo)
	* @param string $template
	* @param string $content
	* @param array $args
	*/
	public function template($template, $content, $args = array()) {
		$game = $this->getGame();

		if (!empty($args))
			extract($args);

		if (file_exists(VIEWS_PATH.$template.'.php'))
			require(VIEWS_PATH.$template.'.php');
	}

	/**
	* Retorna o objeto jogo
	* @access private
	* @return Game
	*/
	private function getGame() {
		$game = null;
		//Game
		if (file_exists(GAME_PATH.'/Game.php')) {
		 	require_once(GAME_PATH.'/Game.php');
		 	$game = Game::getInstance();

		 	if  (!is_subclass_of($game, "JIndie\Game\Game"))
		 		$game = null;
		}

		if ($game == null) {
			require_once(GAME_JI_PATH.'/Game.php');
			$game = JIndie\Game\Game::getInstance();
		}

		return $game;
	}
}