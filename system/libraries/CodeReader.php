<?php
/**
*   JIndie
*   @package JIndie
*   @category Library
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*/

use JIndie\Core\ICode;

class CodeReader {

	/**
	* Linguagem de programação escolhida, representa a pasta
	* @access protected
	* @var string
	*/
	protected $language;

	/**
	* Tipo de código escolhido | Código puro, navegação...
	* @access protected
	* @var string
	*/
	protected $codeName;

	/**
	* Total de linha do código do usuário
	* @access protected
	* @var int
	*/
	protected $totalLine;

	/**
	* Linha atual sendo executada
	* @access protected
	* @var int
	*/
	protected $currentLine;

	/**
	* Código recebido do usuário a ser executado
	* @access protected
	* @var string
	*/
	protected $script;

	/**
	* Class onde ficam as regras do código que será executado
	* @access protected
	* @var ICode
	*/
	protected $code;

	/**
	* Armazena as mensagens de erros
	* @access protected
	* @var array
	*/
	protected $errors = array();

	/**
	* Informa a Linguagem e o código que será verificado
	* @param string $language
	* @param string $codeName
	*/
	public function setRules($language, $codeName) {
		$codeName = ucfirst($codeName);

		if (file_exists(LIBRARIES_PATH.'code/'.$language.'/'.$codeName.'.php')) {
			require_once(LIBRARIES_PATH.'code/'.$language.'/'.$codeName.'.php');

			$this->code = new $codeName;

			if ($this->code instanceof ICode) {
				$this->codeName = $codeName;
				$this->language = $language;	
			} else {
				$this->code = null;
				throw new Exception('Esse código não é um ICODE');
			}
		}
	}

	/**
	* Executa o código do usuário
	* @param string $script
	* @return bool
	*/
	public function runScript($script) {
		if (is_null($this->code))
			throw new Exception("Nenhum código selecionado");


		$this->script = $script;
	}

	/**
	* Retorna os erros no script do usuário
	* @return array;
	*/
	public function getErrors() {
		return $this->errors;
	}

	/**
	* Executa o comando que o usuário digitou e retorna se foi sucesso ou não
	* @access protected
	* @param string $method
	* @param array $param
	* @return bool;
	*/
	protected function execComand($method, $param = array()) {
		$ok = true;
		if (method_exists($this->code, $method)) {
			try {
				if (!empty($param))
					call_user_func_array(array($this->code, $method), $param);
				else 
					call_user_func(array($this->code, $method));		
				}
			} catch (Exception $ex) {
				$ok = false;
				$errors[] = "Linha: " . $this->currentLine . "Erro: '" . $ex->getMessage() . "'";
			}
		} else {
			$ok = false;
			$errors[] = "Linha: " . $this->currentLine . "METODO '" . $method . "' NÂO EXISTE!";
		}

		return $ok;
	}

	/**
	* Verifica se uma condição retorna true ou false
	* @access protected
	* @param string $condition
	* @return bool;
	*/
	protected function checkCondition($condition) {

	}


}