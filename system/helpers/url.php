<?php
/**
* 	JIndie
*	@package JIndie
*	@category Helpers
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

/**
* Retorna o link com a url base definida em app/configurations/Geneneral.php
* @uses site_url('home/page1') 	-> http://seusite.com/home/page1
* @uses site_url() 				-> http://seusite.com/
* @param string $url
* @return string
*/
if (!function_exists('site_url')) {
	function site_url($url = "") {
		$urlBase = Config::getInfo('general', 'urlBase');
		return $urlBase . $url;
	}
}

/**
* Retorna o link com a url base definida em app/configurations/Geneneral.php
* @uses redirect('home/page1') 			-> redirect to http://seusite.com/home/page1
* @uses redirect('http://google.com/')	-> redirect to http://google.com/
* @param string $url
*/
if (!function_exists('redirect')) {
	function redirect($url) {
		if (substr($url, 0, 4) != "http") {
			$urlBase = Config::getInfo('general', 'urlBase');
			$url = $urlBase . $url;
		}

		////////Sava o Game Antes de encerrar as ações
		//Recupera
		if (file_exists(GAME_PATH.'/Game.php')) {
		 	require_once(GAME_PATH.'/Game.php');
		 	$game = Game::getInstance();

		 	if  (!is_subclass_of($this->game, "JIndie\Game\Game"))
		 		$game = null;
		}

		if ($game == null) {
			require_once(GAME_JI_PATH.'/Game.php');
			$game = JIndie\Game\Game::getInstance();
		}

		//Salva
		$session = new Session;
		$session->saveGame($game);
		Log::message(Language::getMessage('log', 'debug_game_save'), 2);

		/////// Redireciona para outra página
		Log::message(Language::getMessage('log', 'debug_url_redirect', array('url' => $url)), 2);
		session_write_close();
		header('Location: ' . $url, true);
		exit();
	}
}







