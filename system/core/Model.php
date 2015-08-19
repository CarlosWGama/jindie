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
abstract class Model extends JI_DefaultStructure {
	

	public function __construct() {
		parent::__construct();
		try {
			$this->loadDatabase('db');	
		} catch (Exception $ex) {
			
		}
		
	}

}