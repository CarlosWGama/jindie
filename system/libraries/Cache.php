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

class Cache {

	/**
	* Diretório onde ficará os dados armazenados
	* @access protected
	* @var string
	*/
	protected $dirCache 	= '';	

	public function __construct() {
		$this->dirCache = Config::getInfo('general', 'dirCache');
		if (empty($this->dirCache))
			$this->dirCache = APP_PATH.'cache/';
	}

	/**
	* @uses $cache->set('lista', array('João', 'Mario'));
	* @uses $cache->set('test', 'Carlos', 50);
	* @param string $file 		//Arquivo onde será armazenado os dados
	* @param mix $data 			//Dados que serão armazenados
	* @param int $expiry 		//Em quantos minutos o conteúdo em cache vai expirar
	*/
	public function set($file, $data, $expiry = 60) {
		$content['expiry'] = date('Y-m-d H:i:s', mktime(date('H'), (date('i') + $expiry), date('s'), date('m'), date('d'), date('Y')));
		$content['data'] = $data;
		file_put_contents($this->dirCache . $file . '.txt', json_encode($content));

		Log::message(Language::getMessage('log', 'debug_cache_set', array('data' =>json_encode($content['data']))), 2);
	}

	/**
	* @param string $file
	* @return mix 
	*/
	public function get($file) {
		//
		Log::message(Language::getMessage('log', 'debug_cache_get', array('file' => $file)), 2);
		
		if (file_exists($this->dirCache . $file . '.txt')) {
			$content = json_decode(file_get_contents($this->dirCache . $file . '.txt'), true);	
			$now = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
			if ($content['expiry'] > $now) {
				Log::message(Language::getMessage('log', 'debug_cache_get_success', array('data' => json_encode($content['data']))), 2);
				return $content['data'];
			}

			Log::message(Language::getMessage('log', 'debug_cache_get_expiry', array('expiry' => date('d/m/Y H:i:s', strtotime($content['expiry'])))), 2);

			unlink($this->dirCache . $file . '.txt');
		} else {
			//
			Log::message(Language::getMessage('log', 'debug_cache_file_not_exists', array('file' => $this->dirCache . $file)), 2);
		}
		
		return false;	
	}
}