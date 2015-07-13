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

final class View {

	/**
	* carrega uma única view
	* @param string $file
	* @param array $args
	*/
	public function render($file, $args = array()) {
		if (!empty($args))
			extract($args);
		if (file_exists(VIEWS_PATH.$file.'.php'))
			require(VIEWS_PATH.$file.'.php');
	}

	/**
	* carrega uma única view dentro de um template (modelo)
	* @param string $template
	* @param string $content
	* @param array $args
	*/
	public function template($template, $content, $args = array()) {
		if (!empty($args))
			extract($args);

		if (file_exists(VIEWS_PATH.$template.'.php'))
			require(VIEWS_PATH.$template.'.php');
	}
}