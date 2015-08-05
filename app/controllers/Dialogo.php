<?php


class Dialogo extends Controller {
	
	public function __construct() {
		parent::__construct();

		$sceneDialog = $this->getGameComponent('SceneDialog');
		$sceneDialog->addSpeech('Carlos', 'Oi!', 'https://lh4.googleusercontent.com/-UcdMsFEkE4E/AAAAAAAAAAI/AAAAAAAAAAA/QQU9brpaDTc/s128-c-k/photo.jpg');
		$sceneDialog->addSpeech('Mylana', 'Oi!', 'https://lh6.googleusercontent.com/-QWQkDptS_D8/AAAAAAAAAAI/AAAAAAAAAAA/WILcTanO7Yc/s128-c-k/photo.jpg');
		$sceneDialog->addSpeech('Carlos', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAA! AAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '');
		$this->game->setScene($sceneDialog);
	}

	public function index() {
		$this->view->render('dialog');
	}
}