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

class MultipleChoice implements IQuestion {
	
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

	/**
	* Alternativas da questão
	* @access protected
	* @var array
	*/
	protected $alternatives = array();

	/**
	* Verifica se as alternativas são do tipo radio ou checkbox
	* @access protected
	* @var bool
	*/
	protected $radio = true;

	public function isRadio() {
		$this->radio = true;
	}

	public function isCheckBox() {
		$this->radio = false;
	}

	public function checkIfRadio() {
		return $this->radio;
	}

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
	* @return 2
	*/
	public function getTypeQuestion() {
		return 2;
	}

	/**
	* Insere uma alternativa
	* @uses $question->addAlternative('Alternativa A', '51');
	* @uses $question->addAlternative('Alternativa B', '25', true);
	* @uses $question->addAlternative(array('alternative' => 'Alternativa C', 'value' => '26');
	* @uses $question->addAlternative(array('alternative' => 'Alternativa D', 'value' => '27', 'corret' => true);
	* @param string|array $alternative 
	* @param string $value
	* @param bool corret
	*/
	public function addAlternative($alternative, $value, $corret = false) {
		if (is_array($alternative)) {
			$corret = (isset($alternative['corret']) ? $alternative['corret'] : false);
			$value = (isset($alternative['value']) ? $alternative['value'] : '');
			$alternative = (isset($alternative['alternative']) ? $alternative['alternative'] : '');
		}

		$this->alternatives[] = array(
			'alternative' 	=> $alternative,
			'value'			=> $value,
			'corret'		=> (bool) $corret
		);
	}

	/**
	* Recupera as alternativas inseridas
	* @return array
	*/
	public function getAlternatives() {
		return $this->alternatives;
	}

	/**
	* Remove todas as alternativas
	*/
	public function clearAlternatives() {
		$this->alternatives = array();
	}

	/**
	* Verifica se a resposta inserida pelo usuário é correta ou não
	* @param string $answer
	* @return bool
	*/
	public function checkAnswer($answer) {
		foreach ($this->alternatives as $alternative) {

			if ($this->checkIfRadio()) {
				if ($alternative['value'] == $answer) 
					return $alternative['corret'];
			} else {
				if (in_array($alternative['value'], $answer) && $alternative['corret'] == true) 
					return $alternative['corret'];
			}
		}

		return false;
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