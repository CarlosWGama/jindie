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

require_once(dirname(__FILE__).'/bootstrap.php');

final class JIndie {
	
	const VERSION = 1.0;

	/**
	* Faz a contagem do tempo que o script começou a rodar
	* @access private
	* @var int
	*/
	private $start;

	/**
	* @param string $errors
	* @param string $charset
	* @param string $logs
	*/
	public function __construct($errors = 'hard', $charset = 'utf8', $logs = 'complete') {
		
		//Log
		Log::message('--------- ' . Language::getMessage('log', 'debug_start') . ' ---------', 2);
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$this->start = $time;
		//

		$this->setError($errors);
		$this->setCharset($charset);
	}

	public function run() {
		try {
			//Executa os Filtros
			$ff = new FilterFactory();
			$ff->runFilters();

			//Executa o Controller
			$cf = new ControllerFactory();		
			$cf->runController();
			
		} catch (Exception $ex) {
			if (in_array($ex->getCode(), array(1, 3))) 
				JI_ERRORS::error404();
			else 
				JI_ERRORS::generalError($ex->getMessage());
		}
	}

	/**
	* @param string $charset
	*/
	public function setError($errors) {
		Log::message('Level Errors - ' . $errors, 2);
		switch ($errors) {
			case "hard":
				ini_set ('display_errors', 1);
				error_reporting(E_ALL);
				break;
			case "medium":
			ini_set ('display_errors', 1);
				error_reporting(E_ALL ^ (E_NOTICE | E_STRICT));
				break;
			case "easy":
				ini_set ('display_errors', 0);
				error_reporting(0);
				break;
		}
		Log::message('Level Errors - ' . $errors, 2);
	}

	/**
	* @param string $charset
	*/
	public function setCharset($charset) {
		Log::message('Charset - ' . $charset, 2);
		switch ($charset) {
			case "utf8":
				header('Content-Type: text/html; charset=utf-8');
				break;
			case "iso":
				header('Content-Type: text/html; charset=iso-8859-1');
				break;
			default: 
				header('Content-Type: text/html; charset=utf-8');;
				break;
		}
	}

	/**
	*Salva no log o tempo de execução do script
	*/
	public function __destruct() {
		//Log
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total = round(($finish - $this->start), 4);
		Log::message(Language::getMessage('log', 'debug_load_page', array('time' => $total)), 2);
		Log::message("--------- " . Language::getMessage('log', 'debug_finish') . " --------- \n", 2);
		exit();
	}

}