<?php
/**
* 	JIndie
*	@package JIndie
*	@category Util
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

class JSONUtil {

	/**
	* @param string $message
	* @return string
	*/
	public static function alert($message) {
		return json_encode(array('type' => 'alert', 'message' => $message));
	}

	/**
	* @param string $question
	* @param string $url
	* @return string
	*/
	public static function question ($question, $url) {
		return json_encode(array('type' => 'confirm', 'question' => $question, 'url' => $url));	
	}

	/**
	* @param string $url
	* @return string
	*/
	public static function redirect($url) {
		return json_encode(array('type' => 'redirect', 'url' => $url));	
	}



}