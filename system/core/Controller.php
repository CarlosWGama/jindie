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

require_once(dirname(__FILE__).'/View.php');

/**
* @abstract
*/
abstract class Controller extends JI_DefaultStructure {
	
	/**
	* @access protected
	* @var Input
	*/
	protected $input = null;

	/**
	* @access protected
	* @var View
	*/
	protected $view = null;

	public function __construct() {
		parent::__construct();
		$this->input = Input::getInstance();
		$this->view = new View();
	}


	
}