<?php


class Question extends Controller {
	

	public function index() {
		$question = $this->getGameComponent('ShortAnswer1');

		$question->setQuestion('O que é isso?');
		$question->setURLToSubmit('question/resposta/1');

		$question->showQuestion();
	}

	public function fechada() {
		$question = $this->getGameComponent('MultipleChoice');		
		$question->setQuestion('O que é isso?');
		$question->setURLToSubmit('question/resposta/2');

		//$question->isCheckBox();
		$question->addAlternative('Alternativa A', 'A');
		$question->addAlternative('Alternativa B', 'B');
		$question->addAlternative('Alternativa C', 'C', true);
		$question->addAlternative('Alternativa D', 'D');
		$question->addAlternative('Alternativa E', 'E');

		$question->showQuestion();
	}

	public function resposta($tipo) {
		$answer = $this->input->post('answer');

		if ($tipo == 1)
			$question = $this->getGameComponent('ShortAnswer1');		
		else {
			$question = $this->getGameComponent('MultipleChoice');		
			$question->isCheckBox();
			$question->addAlternative('Alternativa A', 'A');
			$question->addAlternative('Alternativa B', 'B');
			$question->addAlternative('Alternativa C', 'C', true);
			$question->addAlternative('Alternativa D', 'D');
			$question->addAlternative('Alternativa E', 'E');
		}
		if ($question->checkAnswer($answer)) 
			echo "Acertou!";
		else 
			echo "Errou!";
	}

	
}