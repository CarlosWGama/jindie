<?php

class Jogo extends Model {
	

	public function jogar() {
		echo "JOGOU!!!!";

		$this->game->getScore()->addPoints(10);
	}
}