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

/**
* @abstract
*/
abstract class Filter extends JI_DefaultStructure {
	
	/**
	* URL que será verifica se é igual ou entra no padrão regex da url atual
	* @access protected
	* @uses $url = "/(:any:)" (:any:) qualquer caracter
	* @uses $url = "/(:num:)" (:num:) apenas números
	* @uses $url = "/admin/(:any:)/(:num:)" (:num:) apenas números
	* @var string $url
	*/
	protected $url = "/(:any:)";

	/**
	* @return bool
	*/
	public function check() {
		$input = Input::getInstance();

		if (!empty($this->url)) {
			$filterUrl = WordsUtil::convertToRegex($this->url);
			$url = $input->getFullPath();
			
			if (preg_match($filterUrl, $url))
				return true;
			return false;
		}

		return false;
	}

	/**
	* implementa a ação que será executada, caso a url seja atendida
	* @abstract
	*/

	public abstract function run();


}