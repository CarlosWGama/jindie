<?php


class Command extends Controller {
	

	public function index() {
		echo "<h1>Commands</h1>";

		$this->loadLibrary('codeReader', 'code');


		$script = 'moveUp(1231212);moveDown();moveDown(1);moveLeft();';

		$script = 
		"moveUp(\"\");
		if (true && false) {
			if (false) {
				moveLeft();	
			} else {
				moveRight();	
			}
			moveRight();
			moveLeft();
		} 
		else {
			moveUp();

			if (true) {
				moveRight();	
			} else {
				moveDown();	
				moveDown();	
			}
		}
		";

		if (!$this->code->runScript($script)) {
			print_r($this->code->getError());
		}

		print_r($this->code->getLines());
	}
}