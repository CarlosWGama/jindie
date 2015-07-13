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

final class JI_ERRORS {

	/**
	* Chama página 404
	* @uses JI_ERRORS::error404();
	*/
	public static function error404() {
		$input = Input::getInstance();
		$page = $input->getFullPath();

		$data['title'] = Language::getMessage('error', 'page_404_title');
		$data['message'] = Language::getMessage('error', 'page_404', array('page' => $page));;

		self::view($data);		
		Log::message($data['message'], 1);
	}

	/**
	* Chama página de erros
	* @uses JI_ERRORS::generalError('message');
	* @param string $message
	*/
	public static function generalError($message) {

		$data['title'] = Language::getMessage('error', 'general_error_title');
		$data['message'] = Language::getMessage('error', 'general_error', array('error' => $message));;

		self::view($data);
		Log::message($data['message'], 1);
	}

	/**
	* Chama página
	* @access private
	* @uses self::generalError('message');
	* @param array $data
	*/
	private static function view($data) {
		$view = new View();
		$view->render('errors/error', $data);
		exit();
	}
}