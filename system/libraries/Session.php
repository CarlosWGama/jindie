<?php
/**
* 	JIndie
*	@package JIndie
*	@category Library
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

class Session {
		
	public function __construct() {
		$this->startSession();
	}
	
	/**
	* Responsável por cria a sessão e validar
	* @access protected
	*/
	protected function startSession() {
		if ((session_status() == PHP_SESSION_ACTIVE))
			return;

		//Log 
		Log::message(Language::getMessage('log', 'debug_session_start'), 2);

		session_name("JINDIE_SESSION");
		session_start();

		//Valida se a sessão é a mesma ou outra
		if($this->validateSession()) {
	
			//Valida se é o mesmo usuário ou um acesso externo
			if(!$this->preventHijacking()) {
				Log::message(Language::getMessage('log', 'debug_session_prevent_hijacking'), 2);

				//O acesso não é o mesmo da sessão atual, então o exclui e cria uma nova sessão
				$_SESSION = array();
				$_SESSION['IPaddress'] = md5($_SERVER['REMOTE_ADDR']);
				$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
				$this->regenerateSession();

			// 5% de chance de atualizar o ID da sessão. Caso atualizado, se alguém tentar acessa rusando a sessão antiga, vai perder
			}elseif(rand(1, 100) <= 5){
				$this->regenerateSession();
			}
		} else {
			Log::message(Language::getMessage('log', 'debug_session_validate_fail'), 2);

			$_SESSION = array();
			session_destroy();
			session_start();
		}
	}

	/**
	* Verifica se o acesso é do mesmo usuário
	* @access protected
	* @return bool
	*/
	protected function preventHijacking() {
		if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
			return false;

		if ($_SESSION['IPaddress'] != md5($_SERVER['REMOTE_ADDR']))
			return false;

		if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
			return false;

		return true;
	}

	/**
	* Verifica se a sessão que estão tentando recuperar os dados já expirou ou é obsoleta
	* @access protected
	* @return bool
	*/
	protected function validateSession() {
		if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
			return false;

		if(isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
			return false;
		return true;
	}

	/**
	* Recria a sessão com mesmo ID e seta a antiga como obsoleta para evitar roubo de dados
	* @access protected
	* @return bool
	*/
	protected function regenerateSession() {
		// Se a sessão é obsoleta, então não renova o ID, o que significa perder os dados da sessão
		if(isset($_SESSION['OBSOLETE']) && $_SESSION['OBSOLETE'] == true)
			return;

		Log::message(Language::getMessage('log', 'debug_session_regenerate'), 2);

		// Seta a sessão para expirar em 10 segundos
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + 10;

		// Cria uma nova sessão sem destroir a antiga
		session_regenerate_id(false);

		// Recupera o ID da sessão antiga e impede de serir mais dados nela
		$newSession = session_id();
		session_write_close();

		// Criar uma nova sessão com o mesmo ID, para que os dados não fiquem perdidos
		session_id($newSession);
		session_start();

		// Na nova sessão, remove os dados de expiração e obsoleto.
		unset($_SESSION['OBSOLETE']);
		unset($_SESSION['EXPIRES']);
	}
	
	/**
	* @return bool
	*/
	public function destroy() {
		return session_destroy();
	}
	
	/**
	* @param string $name
	* @param mix $value
	*/
	public function set($name, $value) {
		if (is_object($value)) {
			$_SESSION[$name]['ji_is_obj'] = true;
			$_SESSION[$name]['obj'] = serialize($value);
		}
		else
			$_SESSION[$name] = $value;
	}
	
	/**
	* @param string $name
	* @return mix
	*/
	public function get($name) {
		if (!isset($_SESSION[$name]))
			return null;

		if (isset($_SESSION[$name]['ji_is_obj']) && $_SESSION[$name]['ji_is_obj'] === true) 
			return unserialize($_SESSION[$name]['obj']);
		return $_SESSION[$name];
	}
	
	/**
	* @param string $name
	*/
	public function delete($name) {
		unset($_SESSION[$name]);
	}

	/**
	* Usado exclusivamente para salvar o Status da classe Game de uma requisição para outra
	* @param Game $game
	*/
	public function saveGame($game) {
		return $this->set('ji_object_game', $game);
	}


	/**
	* @return Game
	*/
	public function loadGame() {
		return $this->get('ji_object_game');
	}
}
