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

final class Language {
	
	/**
	* @uses Language::getMessage('log', 'debug_load_page', array('time' => $total))
	* @param string file
	* @param string message
	* @param array values
	*/
	public static function getMessage($file, $message, $values = array()) {
		
		$language = Config::getInfo('general', 'language');
		if (!is_dir(LANGUAGE_PATH.$language))
			$language = 'pt-br';

		require(LANGUAGE_PATH.$language.'/'.$file.'.php');
		if (!empty($values)) {

			$search = self::getKeys($values);
			return str_replace($search, $values, $language[$message]);	
		}
		return $language[$message];
	}

	/**
	* Retorna as keys do array formatadas
	* @access private
	* @param array $array
	* @return array
	*/
	private static function getKeys($array) {
		$keys = array();
		foreach($array as $key => $value)
			$keys[] = "%".strtoupper($key)."%";
		return $keys;
	}

}