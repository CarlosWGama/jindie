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

class WordsUtil {
	
	/**
	* @param string $text
	* @return string
	*/
	public static function convertToRegex($text, $sensitive = true) {

		$replace = array(
			"(:any:)"				=> "(.*)",
			"(:num:)"				=> "(.\d*)",
			"(:string:)"			=> "\"(.*)\""
		);


		if ($sensitive)
			return '/^' . addcslashes(str_replace(array_keys($replace), $replace, $text), '/') . '$/x';
		return '/^' . addcslashes(str_replace(array_keys($replace), $replace, $text), '/') . '$/ix';
	}
}