<?php


class Command extends Controller {
	

	public function index() {
		echo "<h1>Commands</h1>";

		$this->loadLibrary('codeReader', 'code');


		$script = 'moveUp(1231212);moveDown();moveDown(1);moveLeft();';

		if (!$this->code->runScript($script))
			print_r($this->code->getError());
	}
}