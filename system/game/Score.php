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
	
	protected $points = 0;
	protected $negativeScore = false;
	protected $hasGameOverOnZero = false;
	protected $gameOver = false;

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
		if ($this->checkGameOver()) {
			\Log::message(\Language::getMessage('log', 'debug_game_gameover_change_points'), 2);
			return;
		}

		$points + 0; //Converte String para numeral se possível
		
		if (is_numeric($points))
			$this->points = $points;
		else {
			$msg = \Language::getMessage('error', 'game_points_not_numeric');
			\Log::message($msg, 2);
			throw new Exception($msg, 22);
		}

		//Game Over
		if ($this->hasGameOverOnZero && $this->points <= 0) {
			\Log::message(\Language::getMessage('log', 'debug_game_gameover'), 2);
			$this->gameOver = true;
		}

		//Pontos negativos
		if ($this->negativeScore == false && $this->points < 0) {
			\Log::message(\Language::getMessage('log', 'debug_game_points_negative'), 2);
			$this->points = 0;
		}

	}

	public function getPoints() {
		return $this->points;
	}

	public function addPoints($points) {
		if ($this->checkGameOver()) {
			\Log::message(\Language::getMessage('log', 'debug_game_gameover_change_points'), 2);
			return;
		}

		$points + 0; //Converte String para numeral se possível
		
		if (is_numeric($points)) {
			$points = abs($points);
			$this->points += $points;
		} else {
			$msg = \Language::getMessage('error', 'game_points_not_numeric');
			\Log::message($msg, 2);
			throw new Exception($msg, 22);
		}
	}

	public function removePoints($points) {
		if ($this->checkGameOver()) {
			\Log::message(\Language::getMessage('log', 'debug_game_gameover_change_points'), 2);
			return;
		}

		$points + 0; //Converte String para numeral se possível
		
		if (is_numeric($points)) {
			$points = abs($points);
			$this->points -= $points;
		} else {
			$msg = \Language::getMessage('error', 'game_points_not_numeric');
			\Log::message($msg, 2);
			throw new Exception($msg, 22);
		}

		//Game Over
		if ($this->hasGameOverOnZero && $this->points <= 0) {
			\Log::message(\Language::getMessage('log', 'debug_game_gameover'), 2);
			$this->gameOver = true;
		}

		//Pontos Negativos
		if ($this->negativeScore == false && $this->points < 0) {
			\Log::message(\Language::getMessage('log', 'debug_game_points_negative'), 2);
			$this->points = 0;
		}
	}

	public function reset() {
		$this->points = 0;
		$this->gameOver = false;
	}

	public function resetGameOver() {
		$this->gameOver = false;
	}

	public function checkGameOver() {
		return $this->gameOver;
	}

}