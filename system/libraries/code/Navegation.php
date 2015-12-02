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
	* Armazena os dados que são enviados de outras classes para serem trabalhados nos códigos
	* @access protected
	* @var array
	*/
	protected $data;

	public function __construct() {
	}

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
		return true;
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
	* Método que move o personagem para a nova posição e verifica se tem alguma ação a ser ativada
	* @access protected
	*/
	protected function move() {
		$move = false; //Altera a posição do personagem

		unset($this->data['action']);
		if ($this->data['map']->hasTile($this->data['position'])) {
			
			$tile = $this->data['map']->getTile($this->data['position']);

			if (empty($tile['object'])) {
				$move = true;
				$tile['object'] = $this->data['player'];
				$this->data['map']->alterTile($this->data['position'], $tile);
			} 

			if (isset($tile['url']) && !empty($tile['url'])) 
				$this->data['action'] = $tile['url'];
			
		} 
		
		if ($move) //Remove da antiga posição
			$this->data['map']->alterTile($this->data['positionOld'], 'object', '');
		else 	   //Não altera a posição do player
			$this->data['position'] = $this->data['positionOld'];

		unset($this->data['positionOld']);
	}

	/**
	* Move o Personagem para cima (Y - 1)
	*/
	public function moveUp() {
		$this->data['positionOld'] = $this->data['position'];
		$this->data['position']['y']--;
		$this->move();
	}

	/**
	* Move o personagem para cima X vezes ((Y - 1) * número de vezes)
	*/
	public function moveUpSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));
		
		for ($i = 1; $i <= $steps; $i++) 
			$this->moveUp();
		
	}

	/**
	* Move o personagem para baixo (Y + 1)
	*/
	public function moveDown() {
		$this->data['positionOld'] = $this->data['position'];
		$this->data['position']['y']++;
		$this->move();
	}

	/**
	* Move o personagem para baixo X vezes ((Y + 1) * número de vezes)
	*/
	public function moveDownSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveDown();
	}

	/**
	* Move o personagem para Esquerda (X - 1)
	*/
	public function moveLeft() {
		$this->data['positionOld'] = $this->data['position'];
		$this->data['position']['x']--;
		$this->move();
	}

	/**
	* Move o personagem para Esquerda X vezes ((X - 1) * número de vezes)
	*/
	public function moveLeftSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveLeft();
	}

	/**
	* Move o personagem para Direita (X + 1)
	*/
	public function moveRight() {
		$this->data['positionOld'] = $this->data['position'];
		$this->data['position']['x']++;
		$this->move();
	}

	/**
	* Move o personagem para Direita X vezes ((X + 1) * número de vezes)
	*/
	public function moveRightSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveRight();
	}

	/******* OPERATORS *******/
	/**
	* Operador que representa AND
	* @return string
	*/
	public function getAND() {
		return "&&";
	}

	/**
	* Operador que representa OR
	* @return string
	*/
	public function getOR() {
		return "\|\|";
	}

	/******* CONDITIONS AND REPEAT *******/
	/**
	* Estrutura do IF
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
	* Estrutura do ELSE
	* \s*  -> REGEX = Pode conter espaço ou não
	* @return string
	*/
	public function getElseStructure() {
		return "else\s*{";
	}

	/**
	* Estrutura do fim do IF
	* @return string
	*/
	public function getEndIfStructure() {
		return "}";
	}

	/**
	* Estrutura do While
	* [CONDITION] -> Tudo que tiver nesta tag será verificado se retorna true ou false
	* \s*  -> REGEX = Pode conter espaço ou não
	* \(   -> REGEX = Interpreta (
	* \)   -> REGEX = Interpreta )
	* @return string
	*/
	public function getWhileStructure() {
		return "while\s*\([CONDITION]\)\s*{";	
	}

	/**
	* Estrutura do fim do While
	* @return string
	*/
	public function getEndWhileStructure() {
		return "}";	
	}

	/****** DATA ******/
	/**
	* Recebe os dados a serem trabalhos
	* @param array data
	*/
	public function setData($data) {
		$this->data = $data;
	}

	/**
	* Envia os dados trabalhados
	* @return array
	*/
	public function getData() {
		return $this->data;
	}

	/**
	* Limpa todos os dados
	*/
	public function clearData() {
		$this->data = null;
	}
}