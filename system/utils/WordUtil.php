<?php


class WordsUtil {
	
	public static function convertToRegex($text) {
		return '/^' . addcslashes(str_replace(array("(:any:)", "(:num:)"), array(".*", ".\d*"), $text), '/') . '$/';
	}
}