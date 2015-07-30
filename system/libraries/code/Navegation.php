<?php

use JIndie\Code\ICode;

class Navegation implements ICode {


	protected $breakLine = ";";

	public function getBreakLine() {
		return $this->breakLine;
	}

	public function getCaseSensitive() {
		return true;
	}

	public function getCommands() {
		$commands = array(
			'moveUp\s?\((:num:)\)' 			=> 'moveUpSteps',
			'moveUp\s?\(\s?\)' 				=> 'moveUp',

			'moveDown\s?\((:num:)\)' 		=> 'moveDownSteps',
			'moveDown\s?\(\s?\)' 			=> 'moveDown',
			
			'moveLeft\s?\((:num:)\)'		=> 'moveLeftSteps',
			'moveLeft\s?\(\s?\)' 			=> 'moveLeft',

			'moveRight\s?\((:num:)\)' 		=> 'moveRightSteps',
			'moveRight\s?\(\s?\)' 			=> 'moveRight',
		);
		return $commands;
	}

	public function moveUp() {
		echo ("Subiu!<br/>");
	}

	public function moveUpSteps($steps) {
		echo ("Subiu " . $steps . " passos!<br/>");
	}

	public function moveDown() {
		echo ("Desceu!<br/>");	
	}

	public function moveDownSteps($steps) {
		echo ("Desceu " . $steps . " passos!<br/>");
	}

	public function moveLeft() {
		echo ("Esquerda!<br/>");
	}

	public function moveLeftSteps($steps) {
		echo ("Esquerda " . $steps . " passos!<br/>");
	}

	public function moveRight() {
		echo ("Direita!<br/>");
	}

	public function moveRightSteps($steps) {
		echo ("Direita " . $steps . " passos!<br/>");
	}
}