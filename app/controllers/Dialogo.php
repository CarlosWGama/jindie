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

	public function question() {
		$question = $this->getGameComponent('MultipleChoice');		
		$question->setQuestion('O que é isso?');
		$question->setURLToSubmit('question/resposta/2');

		$question->isCheckBox();
		$question->addAlternative('Alternativa A', 'A');
		$question->addAlternative('Alternativa B', 'B');
		$question->addAlternative('Alternativa C', 'C', true);
		$question->addAlternative('Alternativa D', 'D');
		$question->addAlternative('Alternativa E', 'E');

		$this->game->getScene()->setQuestion($question);

		$this->view->render('dialog');	
	}

	public function question2() {
		$question = $this->getGameComponent('ShortAnswer1');		
		$question->setQuestion('O que é isso?');
		$question->setURLToSubmit('question/resposta/2');

		$this->game->getScene()->setQuestion($question);

		$this->view->render('dialog');	
	}
}