<?php
/**
* 	JIndie
*	@package JIndie
*	@category support Class
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

class Config {
	
	/**
	* @uses Config::getConfiguration('general');
	* @param string $file
	*/
	public static function getConfiguration($file) {
		$file = ucfirst($file);
		if (file_exists(APP_PATH.'/configurations/'. $file . '.php')) {
			include(APP_PATH.'/configurations/'. $file . '.php');
			if (isset($config))
				return $config;	
		}
		throw new Exception(Language::getMessage('error', 'config_not_exists', array('config' => $file)), 10);
	}

	/**
	* @uses Config::getInfo('general', 'language');
	* @param string $file
	* @param string $info
	*/
	public static function getInfo($file, $info) {
		$config = self::getConfiguration($file);
		if (isset($config[$info]))
			return $config[$info];
		return null;
	}

	/**
	* Usado para carregar automaticamente algumas classes
	*/
	public static function autoLoad() {
		$config = self::getConfiguration('autoLoad');

		foreach ($config as $type => $classes) {

			foreach ($classes as $class) {
				switch($type) {
					case "Game":
						if (file_exists(GAME_PATH.$class.'.php'))
							require_once(GAME_PATH.$class.'.php');
						break;
				}
			}
		}
	}

}