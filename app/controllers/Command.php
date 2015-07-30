<?php


class Command extends Controller {
	

	public function index() {
		echo "<h1>Commands</h1>";

		$this->loadLibrary('codeReader', 'code');


		$script = 'moveUp(1231212);moveDown();moveDown(1);moveLeft();';

		$script = 
		"moveUp();
		if (moveDown()) {
			if ((moveDown())) {
				moveLeft();	
			} else {
				moveRight();	
			}
			moveRight();
			moveLeft();
		} 
		else {
			moveUp();

			if (moveLeft()) {
				moveRight();	
			} else {
				moveDown();	
				moveDwn();	
			}
		}
		";

		if (!$this->code->runScript($script)) {
			print_r($this->code->getError());
		}
	}
}