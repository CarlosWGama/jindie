<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Components 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

class Score {
	
	private $points = 0;
	private $negativeScore = false;
	private $hasGameOverOnZero = false;
	private $gameOver = false;

	public function activeGameOver() {
		$this->hasGameOverOnZero = true;
	}

	public function desactiveGameOver() {
		$this->hasGameOverOnZero = false;
	}

	public function activeNegativeScore() {
		$this->negativeScore = true;
	}

	public function desactiveNegativeScore() {
		$this->negativeScore = false;
	}

	public function setPoints($points) {
		if (is_int($points) || is_double($points))
			$this->points = $points;
		else 
			throw new Exception();
	}

	public function getPoints() {
		return $this->points;
	}

	public function addPoints($points) {
		if (is_int($points) || is_double($points))
			$this->points += $points;
		else 
			throw new Exception();
	}

	public function removePoints($points) {
		if (is_int($points) || is_double($points))
			$this->points -= $points;
		else 
			throw new Exception();
	}

	public function checkGameOver() {
		return $this->gameOver;
	}

}