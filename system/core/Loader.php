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

final class Loader {
	
	/**
	* carrega o model
	* @param string $model
	*/
	public function model($model) {
		$modelName = ucfirst($model);
	
		if (file_exists(MODELS_PATH.$modelName.'.php')) {
			require_once(MODELS_PATH.$modelName.'.php');

			$m = new $modelName;
			if (is_subclass_of($m, 'Model')) {
				return $m;
			} 
				
			throw new Exception(Language::getMessage('loader', 'class_not_model', array('class' => $modelName)), 4);
		}
		throw new Exception(Language::getMessage('loader', 'model_not_exists', array('model' => $modelName)), 5);
	}

	/**
	* carrega a biblioteca
	* @param string $library
	*/
	public function library($library) {
		$libraryName = ucfirst($library);
		
		if (file_exists(LIBRARIES_PATH.$libraryName.'.php')) {
			require_once(LIBRARIES_PATH.$libraryName.'.php');

			$l = new $libraryName;
		}

		if (file_exists(LIBRARIES_JI_PATH.$libraryName.'.php')) {
			require_once(LIBRARIES_JI_PATH.$libraryName.'.php');

			$l = new $libraryName;
		}
		
		if (isset($l))
			return $l;
		throw new Exception(Language::getMessage('loader', 'library_not_exists', array('library' => $libraryName)), 7);
		//
	}	
}