<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Scene 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

class SceneMap implements IScene {


	protected $navegation = 'PointAndClick';

	protected $player;

	protected $playerPosition;

	protected $map;

	public function showScene() {

	}

	public function check () {
		switch ($this->navegation) {
			case "Code":
				if (empty($this->player))
					throw new Exception();
			case "PointAndClick":

				break;
		}
	}

	public function setPlayer($player, $position) {
		$this->player = $player;
		$this->position = $position;
	}

	public function isCodeNavegation() {
		$this->navegation = 'Code';
	}

	public function isPACNavegation() {
		$this->navegation = 'PointAndClick';
	}
}