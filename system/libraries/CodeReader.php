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

	/**
	* Variável que serve para armazenar informações na montagem das linhas
	* @access private
	* @var array
	*/
	private $auxLexer = array();

	/**
	* Variavel que armazena o momento que o script começou a ser executado, para controlar o tempo máximo de execução
	* @var int
	*/
	private $timeExecution = 0;

	public function __construct() {
		$this->setRules('Navegation');
	}
	/****************** PREPARAÇÃO *********************/
	/**
	* Classifica o script por tipo e dividi por linhas
	* @access private
	* @return array (Linhas)
	*/
	private function explodeLines() {

		//Quebra Linhas
		$this->auxLexer['lines'] 	= explode($this->code->getBreakLine(), $this->script);		
		$this->auxLexer['position'] = 0;
		$this->totalLine 			= 0;

		//Remove linhs em branco no final
		for ($i = (count($this->auxLexer['lines']) - 1); $i >= 0; $i--) {

			if (empty($this->auxLexer['lines'][$i]) || $this->auxLexer['lines'][$i] == "\n")
				unset($this->auxLexer['lines'][$i]);
			else
				break;
		}
		
		return $this->lexerLines();
	}

	/**
	* Classifica as linhas por tipo (COMMAND, IF, ELSE, END-IF, WHILE, END-WHILE)
	* @access private
	* @return array (Linhas)
	*/
	private function lexerLines($returnCondition = null) {
		//Recupera as estruturas do IF e WHILE
		$ifStructure 		= '/^' . str_replace('[CONDITION]', '(.*)', $this->code->getIfStructure()) . '/' . ($this->code->isCaseSensitive()? "" :"i");
		$elseStructure 		= '/^' . $this->code->getElseStructure() . '/'  . ($this->code->isCaseSensitive()? "" :"i");
		$endIfStructure 	= '/^' . $this->code->getEndIfStructure() . '/'  . ($this->code->isCaseSensitive()? "" :"i");
		$whileStructure 	= '/^' . str_replace('[CONDITION]', '(.*)', $this->code->getWhileStructure()) . '/'  . ($this->code->isCaseSensitive()? "" :"i");
		$endWhileStructure 	= '/^' . $this->code->getEndWhileStructure() . '/'  . ($this->code->isCaseSensitive()? "" :"i");

		$lines = array();

		//Atribuid o que a linha representa e seta a linha em sua Key
		for (; $this->auxLexer['position'] < count($this->auxLexer['lines']);) {
			$line = trim($this->auxLexer['lines'][$this->auxLexer['position']]);
			$this->totalLine++;
			$this->auxLexer['position']++;

			//IF
			if (preg_match($ifStructure, $line, $match)) {
				$nextCommand = trim(substr($line, strlen($match[0])));
				
				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);
				
				
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

				if (!is_null($returnCondition) && ($returnCondition == "END-IF")) 
					return $lines;
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
				$nextCommand = trim(substr($line, strlen($match[0])));

				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);

				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "WHILE",
					'condition'		=> $match[1],
					'statements'	=> $this->lexerLines("END-WHILE")
				);
				continue;
			}

			//ENDWHILE
			if (preg_match($endWhileStructure, $line, $match)) {
				$nextCommand = trim(substr($line, strlen($match[0])));

				if (!empty($nextCommand))
					array_splice($this->auxLexer['lines'], $this->auxLexer['position'], 0, $nextCommand);

				$lines[$this->totalLine] = array(
					'line'			=> $match[0],
					'type'			=> "END-WHILE",
				);

				if (!is_null($returnCondition) && ($returnCondition == "END-WHILE")) 
					return $lines;
			}

			//COMMAND
			if (empty($lines[$this->totalLine])) {
				$lines[$this->totalLine] = array(
					'line'		=> $line,
					'type'		=> "COMMAND"
				);				
			} 
		}
		return $lines;
	}

	/******************************* EXECUÇÃO *******************************/
	/**
	* Executa o código do usuário
	* @param string $script
	* @return bool
	*/
	public function runScript($script) {
		if (is_null($this->code)) {
			$msg = Language::getMessage('code_reader', 'error_no_code');
			Log::message($msg, 2);
			throw new Exception($msg, 34);
		}


		$this->script = $script;

		//Gera as linhas de comando
		Log::message(Language::getMessage('code_reader', 'parse_script'), 2);
		$this->currentLine = 0;
		$this->lines = $this->explodeLines();
		Log::message(Language::getMessage('code_reader', 'end_parse_script', array('total_lines' => $this->totalLine)), 2);

		//Seleciona a primeira linha
		
		if ($this->totalLine > 0)  {
			$this->timeExecution = microtime(true);
			try {
				Log::message(Language::getMessage('code_reader', 'start_run_script'), 2);
				return $this->runLines($this->lines);
			} catch (CodeReaderException $ex) {
				$this->error['error'] = array($ex->getMessage());
				return false;
			}
			
		}

		return true;
	}

	/**
	* Executa as linhas do Script
	* @access private
	* @param array $lines
	* @return bool
	*/
	private function runLines($lines) {
		foreach ($lines as $currentLine => $line) {
			
			$this->checkLimitTimeExecution();
			
			$this->currentLine = $currentLine;
			
			//IF
			try { 
				if ($line['type'] == "IF") {
					Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
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

						Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
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
					Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
					while ($this->checkCondition($line['condition'])) {
						if (!$this->runLines($line['statements'])) 
							return false; //ERROR

						$this->currentLine = $currentLine;
						Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
					}
					continue;
				}

				//END IF/WHILE
				if (in_array($line['type'], array("END-IF", "END-WHILE"))) {
					Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
				}
				
				//COMMAND
				if ($line['type'] == "COMMAND") {
					Log::message(Language::getMessage('code_reader', 'run_line', array('code' => $line['line'], 'line' => $this->getCurrentLine())), 2);
					
					//Recupera comando
					$command = $this->getCommand($line['line']);
					$this->execCommand($command);	
				}

			} catch (CodeReaderException $ex) {
				if ($ex->getCode() == 2) {
					$this->error = array(
						'error'		=> $ex->getMessage()
					);	
				} else {
					$this->error = array(
						'line'		=> $this->currentLine,
						'code'		=> $line['line'],
						'error'		=> $ex->getMessage()
					);	
				}
				
				return false;					
			}
		}

		return true;
	}

	/**
	* Verifica se uma condição retorna true ou false
	* @access private
	* @param string $condition
	* @return bool;
	*/
	private function checkCondition($condition) {
		$search = '/\s+(' . $this->code->getAND() . '|' . $this->code->getOR() . ')\s+/'  . ($this->code->isCaseSensitive()? "" :"i");
	
		$return = null;  	//Condição que será retornada
		$position = 0;		//Posição atual da condição na String
		$commands = (preg_split($search, $condition, -1, PREG_SPLIT_OFFSET_CAPTURE)); //Lista de comandos na condição

		foreach ($commands as $subcondition) {

			//Executa o comando
			$command = $this->getCommand(trim($subcondition[0]));
			$result = (bool)$this->execCommand($command);

			//Atribui o valor que será retornado
			if (is_null($return))
				$return = $result;
			else {
				//Caso exista mais de uma condição, verifica se a ação é de AND ou OR
				$operator = substr($condition, $position, $subcondition[1] - $position);
				$position = strlen($subcondition[0]);

				//AND
				if (preg_match('/('.$this->code->getAND().')/'  . ($this->code->isCaseSensitive()? "" :"i"), $operator))     //AND
				$return = ($return && $result); 

				//OR
				elseif (preg_match('/('.$this->code->getOR().')/'  . ($this->code->isCaseSensitive()? "" :"i"), $operator))  //OR
				$return = ($return || $result); 
			}
		}
		Log::message(Language::getMessage('code_reader', 'check_condition', array('line' => $this->getCurrentLine(), 'condition' => $condition, 'return' => ($return? "true" : "false"))), 2);
		return (bool)$return;		
	}

	/**
	* Recupera o método e os parametros de uma linha de comando
	* @access private
	* @param string $line
	* @return array (command)
	*/
	private function getCommand($line) {

		$commandList = $this->code->getCommands();
		$commandLine = false;
		foreach ($commandList as $command => $method) {
			$command = WordsUtil::convertToRegex($command, $this->code->isCaseSensitive());

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

		if ($commandLine == false) 
			throw new CodeReaderException(Language::getMessage('code_reader', 'code_not_found', array('line' => $line, 'current_line' => $this->getCurrentLine())));
		
		return $commandLine;
	}

	/**
	* Executa o comando que o usuário digitou 
	* @access private
	* @param array $command
	* @return bool;
	*/
	private function execCommand($command) {
		if (method_exists($this->code, $command['method'])) {
			Log::message(Language::getMessage('code_reader', 'run_command', array('method' => $command['method'], 'param' => json_encode($command['param']))), 2);			

			if (!empty($command['param']))
				return call_user_func_array(array($this->code, $command['method']), $command['param']);
			else 
				return call_user_func(array($this->code, $command['method']));			
		} 
	}

	/**
	* faz o controle do tempo máximo de execução
	* @access private
	*/
	private function checkLimitTimeExecution() {
		$time = microtime(true);
		$time = round(($time - $this->timeExecution), 4);
		if ($time > $this->code->maxTimeExecution()) 
			throw new CodeReaderException(Language::getMessage('code_reader', 'max_time_execution'), 2);
			
	}
	/****************** GETTERS E SETTERS *************************/
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
		else {
			$msg = Language::getMessage('code_reader', 'error_rules', array('code_name' => $codeName));
			Log::message($msg, 2);
			throw new Exception($msg, 32);
		}

		$code = new $codeName;

		$this->setCode($code);
		$this->codeName = $codeName;
	}

	/**
	* Seta o código que será interpretado na execução do script
	* @param ICode $code
	*/
	public function setCode($code) {
		if ($code instanceof JIndie\Code\ICode) 
			$this->code = $code;
		else {
			$msg = Language::getMessage('code_reader', 'error_icode');
			Log::message($msg, 2);
			throw new Exception($msg, 33);
		}
	}
	/**
	* Retorna o ICode usado
	* @return ICode
	*/
	public function getCode() {
		return $this->code;
	}

	/**
	* Retorna o erro no script do usuário
	* @return array;
	*/
	public function getError() {
		return $this->error;
	}

	/**
	* Retorna a ultima linha executada
	* @return int
	*/
	public function getCurrentLine() {
		return $this->currentLine;
	}

	/**
	* Retorna o total de linhas geradas no script
	* @return int
	*/
	public function getTotalLine() {
		return $this->totalLine;
	}

	/**
	* Retorna as linhas geradas no script
	* @return array
	*/
	public function getLines() {
		return $this->lines;
	}
}