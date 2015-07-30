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

	protected $auxLexer = array();

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

		//Gera as linhas de comando
		$this->lines = $this->explodeLines($script);

		//Seleciona a primeira linha
		
		if ($this->totalLine > 0)  {
			return $this->runLines($this->lines);
		}

		return true;
	}

	private function runLines($lines) {
		foreach ($lines as $currentLine => $line) {
			$this->currentLine = $currentLine;
			//IF
			if ($line['type'] == "IF") {

				//Exec IF
				if ($this->checkCondition($line['condition'])) {
					
					if (!$this->runLines($line['statements']))
						return false;  //ERROR

					$this->currentLine = $currentLine;
				} else {					
					
					//CHECK ELSE
					$nextKey = (array_search($currentLine, array_keys($lines))+1);
					$nextLine = array_keys($lines);
					if (!isset($nextLine[$nextKey]))
						continue;
					$this->currentLine = $nextLine[$nextKey];
					$line = $lines[$this->currentLine];

					//Exec ELSE
					if (!empty($line) && $line['type'] == "ELSE") {
						$this->currentLine = $currentLine;
						
						if (!$this->runLines($line['statements'])) 
							return false; //ERROR
						$this->currentLine = $currentLine;
					}

					$this->currentLine = $currentLine;
				}
				continue;
			}

			//WHILE
			if ($line['type'] == "WHILE") {
				while ($line($line['condition'])) {
					if (!$this->runLines($line['statements'])) 
						return false; //ERROR
				}
				$this->currentLine = $currentLine;
				continue;
			}
			
			//COMMAND
			if ($line['type'] == "COMMAND") {
			
				//Recupera comando
				$command = $this->getCommand($line['line']);
				if ($command != false) {
					$this->execCommand($command);
				} else {
					$this->error = array(
						'line'		=> $this->currentLine,
						'code'		=> $line['line'],
					);
					return false;					
				}
			}
		}

		return true;
	}

	private function explodeLines($scripts) {

		//Quebra Linhas
		$this->auxLexer['lines'] = explode($this->code->getBreakLine(), $scripts);
		$this->auxLexer['position'] = 0;

		//Remove linhs em branco no final
		// if (empty(end($lines)))
		// 	unset($lines[(count($lines) - 1)]);

		return $this->lexerLines();
	}

	private function lexerLines($returnCondition = null) {
		$ifStructure 		= '/^' . str_replace('[CONDITION]', '(.*)', $this->code->getIfStructure()) . '/';
		$elseStructure 		= '/^' . $this->code->getElseStructure() . '/';
		$endIfStructure 	= '/^' . $this->code->getEndIfStructure() . '/';
		$whileStructure 	= '/^' . str_replace('[CONDITION]', '(.*)', $this->code->getWhileStructure()) . '/';
		$endWhileStructure 	= '/^' . $this->code->getEndWhileStructure() . '/';

		$lines = array();

		for (; $this->auxLexer['position'] < count($this->auxLexer['lines']);) {
			$line = trim($this->auxLexer['lines'][$this->auxLexer['position']]);
			$this->totalLine++;
			$this->auxLexer['position']++;

			//IF
			if (preg_match($ifStructure, $line, $match)) {
				$nextCommand = trim(substr($line, strlen($match[0])));
				
				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);
				//$this->lexerLines();	
				
				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "IF",
					'condition'		=> $match[1],
					'statements'	=> $this->lexerLines("END-IF")
				);
				continue;
			}

			//ENDIF
			if (preg_match($endIfStructure, $line, $match)) {
				$nextCommand = trim(substr($line, strlen($match[0])));
				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);

				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "END-IF",
				);
			}

			//ELSE
			if (preg_match($elseStructure, $line, $match)) {
				$nextCommand = trim(substr($line, strlen($match[0])));

				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);

				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "ELSE",
					'statements'	=> $this->lexerLines("END-IF")
				);
				continue;
			}	

			//WHILE
			if (preg_match($whileStructure, $line, $match)) {
				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "WHILE",
					'condition'		=> $match[1],
					'statements'	=> $this->lexerLines("END-WHILE")
				);
			}

			//ENDWHILE
			// if (preg_match($endWhileStructure, $line, $match)) {
			// 	$lines2[$this->totalLine] = array(
			// 		'line'			=> $line,
			// 		'type'			=> "END-WHILE",
			// 	);				
			// }

			//COMMAND
			if (empty($lines[$this->totalLine])) {
				$lines[$this->totalLine] = array(
					'line'		=> $line,
					'type'		=> "COMMAND"
				);				
			} else {
				if (!is_null($returnCondition) && ($returnCondition == $lines[$this->totalLine]['type']))
					return $lines;
			}
		}
		return $lines;
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
				$this->error = "Linha: " . $this->currentLine . "Erro: '" . $ex->getMessage() . "'";
			}
		} else {
			$ok = false;
			$this->error = "Linha: " . $this->currentLine . "METODO '" . $command['method'] . "' NÂO EXISTE!";
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

		$and = $this->code->getAND();
		$or = $this->code->getOR();
		$leftParen = $this->code->getLeftParen();
		$rightParen = $this->code->getRightParen();

		return false;		
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