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

use JIndie\Code\ICode;

class CodeReader {

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
	* Linhas de código
	* @access protected
	* @var array
	*/
	protected $lines;

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
	* Armazena a mensagen de erro
	* @access protected
	* @var array
	*/
	protected $error = array();

	public function __construct() {
		$this->setRules('Navegation');
	}

	/**
	* Informa o código que será verificado
	* @param string $codeName
	*/
	public function setRules($codeName) {
		$codeName = ucfirst($codeName);

		if (file_exists(LIBRARIES_PATH.'code/'.$codeName.'.php')) 
			require_once(LIBRARIES_PATH.'code/'.$codeName.'.php');
		elseif(file_exists(LIBRARIES_JI_PATH.'code/'.$codeName.'.php')) 
			require_once(LIBRARIES_JI_PATH.'code/'.$codeName.'.php');
		else
			throw new Exception('Código não existe');

		$code = new $codeName;

		$this->setCode($code);
		$this->codeName = $codeName;
	}

	public function setCode($code) {
		if ($code instanceof JIndie\Code\ICode) 
			$this->code = $code;
		else 
			throw new Exception('Esse código não é um ICODE');
	}

	/**
	* Executa o código do usuário
	* @param string $script
	* @return bool
	*/
	public function runScript($script) {
		if (is_null($this->code))
			throw new Exception("Nenhum interpretador de código selecionado");


		$this->script = $script;

		//Recupera  as linhas
		$this->lines = explode($this->code->getBreakLine(), $script);

		//Remove a ultima linha caso seja vázia
		if (empty(end($this->lines)))
			unset($this->lines[(count($this->lines) - 1)]);

		//Recupera o total de linhas
		$this->totalLine = count($this->lines);

		//Seleciona a primeira linha
		$this->currentLine = 0;
		$line = false;
		if ($this->totalLine > 0) 
			$line = $this->nextLine();

		//Executa linha por linha
		while ($line != false) {			
			
			if (!empty($line)) {
			
				//Recupera comando
				$command = $this->getCommand($line);
				if ($command != false) {
					$this->execCommand($command);
				} else {
					$this->error = array(
						'line'		=> $this->currentLine,
						'code'		=> $line,
					);
					return false;					
				}
			}

			$line = $this->nextLine();			
		}

		return true;

	}

	protected function nextLine() {
		if ($this->currentLine < $this->totalLine) {
			$this->currentLine++;
			return $line = trim($this->lines[($this->currentLine-1)]);
		}

		return false;
	}

	/**
	* Retorna os erros no script do usuário
	* @return array;
	*/
	public function getError() {
		return $this->error;
	}

	/**
	* Executa o comando que o usuário digitou e retorna se foi sucesso ou não
	* @access protected
	* @param string $method
	* @param array $param
	* @return bool;
	*/
	protected function execCommand($command) {
		$ok = true;
		if (method_exists($this->code, $command['method'])) {
			try {
				if (!empty($command['param']))
					call_user_func_array(array($this->code, $command['method']), $command['param']);
				else 
					call_user_func(array($this->code, $command['method']));		
				
			} catch (Exception $ex) {
				$ok = false;
				$errors[] = "Linha: " . $this->currentLine . "Erro: '" . $ex->getMessage() . "'";
			}
		} else {
			$ok = false;
			$errors[] = "Linha: " . $this->currentLine . "METODO '" . $command['method'] . "' NÂO EXISTE!";
		}

		return $ok;
	}

	protected function getCommand($line) {
		$commandList = $this->code->getCommands();
		$commandLine = false;
		foreach ($commandList as $command => $method) {
			$command = WordsUtil::convertToRegex($command, $this->code->getCaseSensitive());
			//print_r($command);die;
			
			if (preg_match($command, $line, $match)) {				

				$param = array();
				if (count($match) > 1) 
					$param = array_slice($match, 1);

				$commandLine = array(
					'method' 	=> $method,
					'param'		=> $param
				);
				break;
			}
		}
		
		return $commandLine;
	}

	/**
	* Verifica se uma condição retorna true ou false
	* @access protected
	* @param string $condition
	* @return bool;
	*/
	protected function checkCondition($condition) {

	}


	public function getCurrentLine() {
		return $this->currentLine;
	}

	public function getTotalLine() {
		return $this->totalLine;
	}

	public function getLines() {
		return $this->lines;
	}
}