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

	protected $session;

	public function __construct() {
		$this->session = new Session();
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

	private function move($data) {
		if ($data['map']->hasTile($data['position'])) {
			$tile = $data['map']->getTile($data['position']);

			if (empty($tile['object'])) {
				$tile['object'] = $data['player'];
				$data['map']->alterTile($data['position'], $tile);
			}
		} else {
			$tile = $data['map']->getDefaultTile();
			$tile['object'] = $data['player'];
			$data['map']->addTileAtPosition($data['position'], $tile);
		}
	}

	/**
	* 
	*/
	public function moveUp() {
		//Start
		$data = $this->session->get('ji_move_player');

		//Remove oldPosition
		$data['map']->alterTile($data['position'], 'object', '');


		//moveUp
		$data['position']['y']--;
		$this->move($data);

		//End
		$this->session->set('ji_move_player', $data);
	}

	/**
	* 
	*/
	public function moveUpSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));
		
		for ($i = 1; $i <= $steps; $i++) 
			$this->moveUp();
		
	}

	/**
	* 
	*/
	public function moveDown() {
		//Start
		$data = $this->session->get('ji_move_player');

		//Remove oldPosition
		$data['map']->alterTile($data['position'], 'object', '');


		//moveUp
		$data['position']['y']++;
		$this->move($data);

		//End
		$this->session->set('ji_move_player', $data);	
	}

	/**
	* 
	*/
	public function moveDownSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveDown();
	}

	/**
	* 
	*/
	public function moveLeft() {
		//Start
		$data = $this->session->get('ji_move_player');

		//Remove oldPosition
		$data['map']->alterTile($data['position'], 'object', '');


		//moveUp
		$data['position']['x']--;
		$this->move($data);

		//End
		$this->session->set('ji_move_player', $data);	
	}

	/**
	* 
	*/
	public function moveLeftSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveLeft();
	}

	/**
	* 
	*/
	public function moveRight() {
		//Start
		$data = $this->session->get('ji_move_player');

		//Remove oldPosition
		$data['map']->alterTile($data['position'], 'object', '');


		//moveUp
		$data['position']['x']++;
		$this->move($data);

		//End
		$this->session->set('ji_move_player', $data);		
	}

	/**
	* 
	*/
	public function moveRightSteps($steps) {
		if ($steps < 0)
			throw new CodeReaderException(Language::getMessage("navegation", 'move_negative'));

		for ($i = 1; $i <= $steps; $i++) 
			$this->moveRight();
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