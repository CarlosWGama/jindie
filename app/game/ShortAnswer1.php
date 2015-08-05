<?php
require_once(GAME_JI_PATH.'ShortAnswer.php');

use JIndie\Game\ShortAnswer;

class ShortAnswer1 extends ShortAnswer {


	public function checkAnswer($answer) {
		if ($answer == "Carlos")
			return true;
		return false;
	}

}