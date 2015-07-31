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

class Navegation implements ICode {

	/**
	* Quebra de linha dos comandos do Script 
	* @access protected
	* @var string
	*/
	protected $breakLine = ";";

	/**
	* Tempo máximo de execução
	* @access protected
	* @var int
	*/
	protected $timeExecution = 3;

	/**
	* @return string
	*/
	public function getBreakLine() {
		return $this->breakLine;
	}

	/**
	* @return int
	*/
	public function maxTimeExecution() {
		return $this->timeExecution;
	}

	/**
	* @return bool
	*/
	public function isCaseSensitive() {
		return false;
	}

	/**
	******* COMMANDS *******
	* @return array
	*/
	public function getCommands() {
		$commands = array(
			'moveUp\s*\((:num:)\)' 			=> 'moveUpSteps',
			'moveUp\s*\(\s*\)' 				=> 'moveUp',

			'moveDown\s*\((:num:)\)' 		=> 'moveDownSteps',
			'moveDown\s*\(\s*\)' 			=> 'moveDown',
			
			'moveLeft\s?\((:num:)\)'		=> 'moveLeftSteps',
			'moveLeft\s?\(\s*\)' 			=> 'moveLeft',

			'moveRight\s*\((:num:)\)' 		=> 'moveRightSteps',
			'moveRight\s*\(\s*\)' 			=> 'moveRight',

			'false' 						=> 'checkFalse',
			'true' 							=> 'checkTrue',
		);
		return $commands;
	}

	/**
	* @return false
	*/
	public function checkFalse() {
		return false;
	}

	/**
	* @return true
	*/
	public function checkTrue() {
		return true;
	}

	/**
	* 
	*/
	public function moveUp() {
		echo ("Subiu!<br/>");
	}

	/**
	* 
	*/
	public function moveUpSteps($steps) {
		if (!is_int($steps))
			throw new CodeReaderException("AAAA");
		echo ("Subiu " . $steps . " passos!<br/>");
	}

	/**
	* 
	*/
	public function moveDown() {
		echo ("Desceu!<br/>");	
		return false;
	}

	/**
	* 
	*/
	public function moveDownSteps($steps) {
		echo ("Desceu " . $steps . " passos!<br/>");
	}

	/**
	* 
	*/
	public function moveLeft() {
		echo ("Esquerda!<br/>");
	}

	/**
	* 
	*/
	public function moveLeftSteps($steps) {
		echo ("Esquerda " . $steps . " passos!<br/>");
	}

	/**
	* 
	*/
	public function moveRight() {
		echo ("Direita!<br/>");
	}

	/**
	* 
	*/
	public function moveRightSteps($steps) {
		echo ("Direita " . $steps . " passos!<br/>");
	}

	/******* OPERATORS *******/
	/**
	* @return string
	*/
	public function getAND() {
		return "&&";
	}

	/**
	* @return string
	*/
	public function getOR() {
		return "\|\|";
	}

	/******* CONDITIONS AND REPEAT *******/
	/**
	* [CONDITION] -> Tudo que tiver nesta tag será verificado se retorna true ou false
	* \s*  -> REGEX = Pode conter espaço ou não
	* \(   -> REGEX = Interpreta (
	* \)   -> REGEX = Interpreta )
	* @return string
	*/
	public function getIfStructure() {
		return "if\s*\([CONDITION]\)\s*{";
	}

	/**
	* \s*  -> REGEX = Pode conter espaço ou não
	* @return string
	*/
	public function getElseStructure() {
		return "else\s*{";
	}

	/**
	* @return string
	*/
	public function getEndIfStructure() {
		return "}";
	}

	/**
	* [CONDITION] -> Tudo que tiver nesta tag será verificado se retorna true ou false
	* \s*  -> REGEX = Pode conter espaço ou não
	* \(   -> REGEX = Interpreta (
	* \)   -> REGEX = Interpreta )
	* @return string
	*/
	public function getWhileStructure() {
		return "WHILE\s*\([CONDITION]\)\s*{";	
	}

	/**
	* @return string
	*/
	public function getEndWhileStructure() {
		return "}";	
	}
}