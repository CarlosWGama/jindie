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


final class ControllerFactory {
	
	
	public function runController() {
		$input = Input::getInstance();
		$controller = null;

		//Controller
		$nameController = $input->getController();
		if (empty($nameController)) //URL Vazia
			$nameController = Config::getInfo('general', 'defaultController');
		
		$pathController = CONTROLLERS_PATH.$input->getFolder().$nameController.'.php';
		
		//Verifica se controle existe e o chama
		if (file_exists($pathController)) {
			require_once($pathController);
			
			Log::message(Language::getMessage('log', 'debug_controller_start', array('controller' => $nameController)), 2);
			
			$controller = new $nameController;


			if (!is_subclass_of($controller, 'Controller')) {
				Log::message(Language::getMessage('log', 'debug_controller_not_controller', array('controller' => $nameController)), 2);
			
				throw new Exception(Language::getMessage('class', 'class_not_controller', array('class' => $nameController)), 1);
			}
		} else {
			Log::message(Language::getMessage('log', 'debug_controller_not_exists', array('controller' => $nameController)), 2);

			throw new Exception(Language::getMessage('class', 'class_not_exists', array('class' => $nameController)), 1);
		}

		//Metodo
		$method = $input->getMethod();
		$args = ($input->getArguments() ? $input->getArguments() : array());
		if (empty($method)) $method = "index";

		if (method_exists($controller, $method)) {
			try {

				if (empty($args)) { //Sem argumentos
					Log::message(Language::getMessage('log', 'debug_controller_run', array('controller' => $nameController, 'method' => $method)), 2);
					
					call_user_func(array($controller, $method));

				} else { //Com argumentos
					Log::message(Language::getMessage('log', 'debug_controller_run_args', array('controller' => $nameController, 'method' => $method, 'args' => json_encode($args))), 2);
					
					call_user_func_array(array($controller, $method), $args);					
				}
			} catch (Exception $ex) {
				Log::message(Language::getMessage('log', 'debug_controller_method_fail', array('method' => $method, 'controller' => $nameController, 'cause' => $ex->getMessage() . " (#" . $ex->getCode() . ")")), 2);

				throw new Exception(Language::getMessage('class', 'method_fail', array('method' => $method, 'cause' => $ex->getMessage() . " (#" . $ex->getCode() . ")")), 2);	
			}

		} else {
			Log::message(Language::getMessage('log', 'debug_controller_method_not_exists', array('method' => $method, 'controller' => $nameController)), 2);

			throw new Exception(Language::getMessage('class', 'method_not_exists', array('method' => $method)), 3);
		}
	}

}