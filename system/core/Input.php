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

final class Input {

	/**
	* @access private
	* @var Input
	*/
	private static $instance = null;
	
	/**
	* variaveis relacionadas a url
	* @access private
	* @var string $controller
	* @var string $method
	* @var array $args
	* @var string $folder
	* @var string $path
	*/
	private $controller = '';
	private $method = '';
	private $args = array();
	private $folder = '';
	private $path;

	/**
	* Dados sendo transferidos entre páginas
	* @access private
	* @var array $post
	* @var array $get
	* @var array $file
	*/
	private $post = array();
	private $get = array();
	private $file = array();

	/**
	* @access private
	*/
	private function __construct() {
		$this->extractUrl();
	}

	/**
	* @return Input
	*/
	public static function getInstance() {
		if (self::$instance == null)
			self::$instance = new Input();
		return self::$instance;
	}

	//==============================================
	//Init
	/**
	* Separa os dados da url 
	* @access private
	*/
	private function extractUrl() {
		$path = '';
		if (isset($_GET['path'])) {
			$path = $_GET['path'];
			unset($_GET['path']);	
		}
		
		$this->cleanInput();

		//URL
		$this->path = explode('/', $path);
		Log::message(Language::getMessage('log', 'debug_input_url', array('url' => $path)), 2);

		$i = 0;
		if ($i > (count($this->path) - 1)) return;
		//Diretório
		while (is_dir(CONTROLLERS_PATH.$this->folder.$this->path[$i])) {
			$this->folder .= $this->path[$i].'/';
			$i++;

			if ($i > (count($this->path) - 1)) return;
		}
		if (!empty($this->folder))
			Log::message(Language::getMessage('log', 'debug_input_folder', array('folder' => $this->folder)), 2);

		//Classe
		$this->controller = ucfirst($this->path[$i]);
		$i++;

		Log::message(Language::getMessage('log', 'debug_input_controller', array('controller' => $this->controller)), 2);
		if ($i > (count($this->path) - 1)) return;
				
		//Metodo
		$this->method = $this->path[$i];
		$i++;
		Log::message(Language::getMessage('log', 'debug_input_method', array('method' => $this->method)), 2);
		if ($i > (count($this->path) - 1)) return;

		//Atributos
		for (;$i < count($this->path); $i++) 
			$this->args[] = $this->path[$i];
		Log::message(Language::getMessage('log', 'debug_input_args', array('args' => json_encode($this->args))), 2);

	}

	/**
	* Verifica o método de envio, se foi post ou não (get)
	* @return bool
	*/
	public function isPost() {
		return ($_SERVER['REQUEST_METHOD'] === 'POST');
	}

	/**
	* Faz o filtro dos dados passado pelo usuário evitando sql
	* @access private
	*/
	private function cleanInput() {
		
		if (!empty($_GET)) {
			foreach ($_GET as $k => $v) {
				if (is_array ($v)) {
					foreach ($v as $k2 => $v2)
						$v[$k2] = htmlspecialchars($v2, ENT_QUOTES);
					$this->get[$k] = $v;
				} else 
					$this->get[$k] = htmlspecialchars($v, ENT_QUOTES);
			}
			//$this->get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
			Log::message(Language::getMessage('log', 'debug_input_get', array('data' => json_encode($this->get))), 2);
		}

		if (!empty($_POST)){
			foreach ($_POST as $k => $v) {
				if (is_array ($v)) {
					foreach ($v as $k2 => $v2)
						$v[$k2] = htmlspecialchars($v2, ENT_QUOTES);
					$this->post[$k] = $v;
				} else 
					$this->post[$k] = htmlspecialchars($v, ENT_QUOTES);
			}
			//$this->post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
			Log::message(Language::getMessage('log', 'debug_input_post', array('data' => json_encode($this->post))), 2);
		}

		if (!empty($_FILES)) {
			foreach ($_FILES as $key => $file) {
				//file
				if (is_string($file['tmp_name'])) {
					if(file_exists($file['tmp_name']) || is_uploaded_file($file['tmp_name'])) 
 					$this->file[$key] = $file;	
				} else if (is_array($file['tmp_name'])) { //multiple files
					foreach ($file['tmp_name'] as $key2 => $value) {
						if(file_exists($file['tmp_name'][$key2]) || is_uploaded_file($file['tmp_name'][$key2])) 
 							$this->file[$key] = $file;	
					}
				}
			}
			Log::message(Language::getMessage('log', 'debug_input_file', array('data' => json_encode($this->file))), 2);	
		}
	}


	//==============================================
	//Functions 
	/**
	* @return mix
	*/
	public function get($value) {
		return (isset($this->get[$value]) ? $this->get[$value] : '');
	}

	/**
	* @return array
	*/
	public function getAll() {
		return $this->get;
	}

	/**
	* @return mix
	*/
	public function post($value) {
		return (isset($this->post[$value]) ? $this->post[$value] : '');
	}

	/**
	* @return array
	*/
	public function postAll() {
		return $this->post;
	}

	/**
	* @return array
	*/
	public function file($value) {
		return (isset($this->file[$value]) ? $this->file[$value] : null);
	}

	/**
	* @return array
	*/
	public function fileAll() {
		return $this->file;
	}

	/**
	* @return string
	*/
	public function getUrlPosition($position) {
		return $this->path[++$position];
	}

	/**
	* @return string
	*/
	public function getFullPath() {
		return "/" . implode('/', $this->path);
	}

	/**
	* @return string
	*/
	public function getCurrentURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
 		return $pageURL;
	}

	//==============================================
	//Gets
	/**
	* @return string
	*/
	public function getController() {
		return $this->controller;
	}

	/**
	* @return string
	*/
	public function getMethod() {
		return $this->method;
	}

	/**
	* @return string
	*/
	public function getArguments() {
		return $this->args;
	}	

	/**
	* @return string
	*/
	public function getFolder() {
		return $this->folder;
	}
}