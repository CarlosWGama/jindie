<?php
/**
* 	JIndie
*	@package JIndie
*	@category Library
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

class Upload {
	
	/**
	* @access protected
	* @var Input
	*/	
	protected $input = null;

	/**
	* @access protected
	* @var string
	*/	
	protected $error = '';

	public function __construct() {
		$this->input = Input::getInstance();
	}


	/**
	* Realiza o upload
	* @param string $file 		| Nome do arquivo no input
	* @param string $dir 		| Diretório onde será salvo, caso não tenha será na raiz
	* @param string $fileName 	| O novo nome do arquivo. Se não for setado, pega o mesmo
	* @param bool $overwrite 	| Sobrescreve o arquivo antigo, caso já exista um com o mesmo nome no diretório
	* @return bool
	*/
	public function doUpload($file, $dir = null, $fileName = null, $overwrite = false) {
		$file = $this->input->file($file);

		//Recupera nome e extensão do arquivo
		$name = explode('.', $file['name']);
		$ext = end($name);

		$name = implode('.', $name);
		$name = substr($name, 0, - (1+strlen($ext)));

		if (!empty($fileName))
			$name = $fileName;

		//Checa diretório
		if (empty($dir))
			$dir = ROOT_PATH;

		if (!is_dir($dir)) {
			$this->error = Language::getMessage('upload', 'dir_not_exist', array('dir' => $dir));

			Log::message($this->error, 2);
			Log::message($this->error, 1);
			return false;
		}

		if (!is_writable($dir)) {
			$this->error = Language::getMessage('upload', 'dir_not_permission', array('dir' => $dir));

			Log::message($this->error, 2);
			Log::message($this->error, 1);
			return false;
		}

		if (substr($dir, -1) != '/')
			$dir .= '/';

		//Não sobreescrever
		$destiny = $dir.$name.'.'.$ext;
		
		if (!$overwrite) {
			if (file_exists($destiny)) {
				for ($i = 1; $i < 1000; $i++) {
					$destiny = $dir.$name.'_'.$i.'.'.$ext;
					
					if (!file_exists($destiny)) 	
						break;
					
				}
			} 	
		}

		Log::message(Language::getMessage('upload', 'do_upload', array('file' => $file['name'], 'destiny' => $destiny, 'overwrite' => ($overwrite ? 'SIM' : 'NÃO'))), 2);


		return move_uploaded_file($file['tmp_name'], $destiny);
	}

	/**
	* Realiza o upload de multi files "arquivo[]"
	* @param string $file 			| Nome do campo input
	* @param string $dir 			| Diretório onde os arquivos serão salvos
	* @param array $filesName		| Nome dos novos arquivos
	* @param bool $overwrite		| Sobrescreve o arquivo antigo, caso já exista um com o mesmo nome no diretório 
	* @return bool
	*/
	public function doMultiUpload($file, $dir = null, $filesName = array(), $overwrite = false) {
		$files = $this->input->file($file);

		$files['ext'] = array();
		$files['newName'] = array();
		foreach ($files['name'] as $key => $fileName) {
			//Recupera nome e extensão do arquivo
			$name = explode('.', $fileName);
			$ext = end($name);

			$name = implode('.', $name);
			$name = substr($name, 0, - (1+strlen($ext)));

			if (is_array($filesName)) {
				if (!empty($filesName[$key]))
					$name = $filesName[$key];	
			}

			//
			$files['ext'][$key] = $ext;
			$files['newName'][$key] = $name;
		}
		

		//Checa diretório
		if (empty($dir))
			$dir = ROOT_PATH;

		if (!is_dir($dir)) {
			$this->error = Language::getMessage('upload', 'dir_not_exist', array('dir' => $dir));

			Log::message($this->error, 2);
			Log::message($this->error, 1);
			return false;
		}

		if (!is_writable($dir)) {
			$this->error = Language::getMessage('upload', 'dir_not_permission', array('dir' => $dir));

			Log::message($this->error, 2);
			Log::message($this->error, 1);
			return false;
		}

		if (substr($dir, -1) != '/')
			$dir .= '/';

		foreach ($files['newName'] as $key => $file) {

			//Não sobreescrever
			$destiny = $dir.$file.'.'.$ext;
			
			if (!$overwrite) {
				if (file_exists($destiny)) {
					for ($i = 1; $i < 1000; $i++) {
						$destiny = $dir.$file.'_'.$i.'.'.$ext;
						
						if (!file_exists($destiny)) 	
							break;
						
					}
				} 	
			}
			Log::message(Language::getMessage('upload', 'do_upload', array('file' => $file, 'destiny' => $destiny, 'overwrite' => ($overwrite ? 'SIM' : 'NÃO'))), 2);
			
			if (!move_uploaded_file($files['tmp_name'][$key], $destiny)) {
				$this->error = "Não foi possível fazer o upoload do arquivo '" . $files[$key]['name'] . "'";
				return false;
			}
		}

		return true;
	}

	/**
	* @return string
	*/
	public function getError(){
		return $this->error;
	}

}