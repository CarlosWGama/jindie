<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Question
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

abstract class ShortAnswer implements IQuestion {

	/**
	* Enunciado da questão
	* @access protected
	* @var string
	*/
	protected $question;

	/**
	* URL para onde será enviado a resposta
	* @access protected
	* @var string
	*/
	protected $url;

	public abstract function checkAnswer($answer);

	/**
	* Recupera o enunciado da questão
	* @return string
	*/
	public function getQuestion() {
		return $this->question;
	}

	/**
	* Insere o enunciado da questão
	* @param string $question
	*/
	public function setQuestion($question) {
		$this->question = $question;
	}
	
	/**
	* Recuper ao Identificador do tipo da Questão
	* @return 1
	*/
	public function getTypeQuestion() {
		return 1;
	}

	/**
	* Insere a URL para onde será enviada a resposta
	* @uses $question->setURLToSubmit('pagina/resposta');				 -> http://seusite/pagina/resposta
	* @uses $question->setURLToSubmit('http://seusite/pagina/resposta'); -> http://seusite/pagina/resposta
	* @param string $url
	*/
	public function setURLToSubmit($url) {
		if (substr($url, 0, 4) != 'http') {
			$urlBase = \Config::getInfo('general', 'urlBase');
			$url = $urlBase . $url;
		}
		$this->url = $url;
	}

	/**
	* URL para onde enviará a resposta do usuário
	* @return string
	*/
	public function getURLToSubmit() {
		return $this->url;
	}

	/**
	* Gera um HTML com a questão estruturada
	* @param bool $returnHTML
	* @return string
	*/
	public function showQuestion($returnHTML = false) {
		$question = $this;
		
		if ($returnHTML) {
			ob_start();
			require(VIEWS_PATH.'game/question.php');
			return ob_get_clean();
		} else
			require(VIEWS_PATH.'game/question.php');
	}
}