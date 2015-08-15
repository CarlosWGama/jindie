<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Scene 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

class SceneDialog implements IScene {
	
	/**
	* Guarda as informações sobre as cores usadas na Scene
	* @access protected
	* @var array
	*/
	protected $colors = array(
		'balloon'			=> 'white',
		'text'				=> 'black',
		'backgroundName'	=> 'orange',
	);

	/**
	* Guarda as falas dos personagens
	* @access protected
	* @var array
	*/
	protected $speechs = array();

	/**
	* @access protected
	* @var IQuestion
	*/
	protected $question;

	/**
	* Adiciona a fala dos personagens
	* @uses $scene->addSpeech('carlos', 'Hello World', 'link-da-imagem');
	* @uses $scene->addSpeech(array('text' => 'carlos', 'avatar' => 'Hello World', 'name' => 'link-da-imagem'));
	* @param string|array $name
	* @param string $text
	* @param string $avatar
	*/
	public function addSpeech($name, $text, $avatar = null) {
		if (is_array($name)) {
			$text 	= (!empty($name['text']) ? $name['text'] : null);
			$avatar = (!empty($name['avatar']) ? $name['avatar'] : null);
			$name 	= (!empty($name['name']) ? $name['name'] : null);
		}

		$this->speechs[] = array(
			'name'		=> $name,
			'text'		=> $text,
			'avatar'	=> $avatar
		);
	}

	/**
	* Retorna as falas
	* @return array
	*/
	public function getSpeechs() {
		return $this->speechs;
	}

	/**
	* Limpa todas as falas armazenadas
	*/
	public function clearDialog() {
		$this->speechs = array();
	}

	/**
	* Realiza a validação da Scene
	*/
	public function check() {
		\Log::message(\Language::getMessage('log', 'debug_scene_start_check', array('scene' => "SceneDialog")), 2);

		if (count($this->speechs) == 0) 
			throw new \Exception(\Language::getMessage('scenes', 'scenedialog_no_dialog'), 40);	

		if (!is_null($this->question) && !($this->question instanceof IQuestion)) 
			throw new \Exception(\Language::getMessage('scenes', 'scenedialog_no_iquestion'), 41);	

		\Log::message(\Language::getMessage('log', 'debug_scene_end_check', array('scene' => "SceneDialog")), 2);
	}

	/**
	* Exibe o conteúdo da Scene
	* @return string
	*/
	public function showScene() {
		\Log::message(\Language::getMessage('log', 'debug_scene_show_scene', array('scene' => "SceneDialog")), 2);

		$speechs = $this->getSpeechs();
		$colors = $this->getColors();
		$question = (!is_null($this->question) ? $this->question->showQuestion(true) : '');

		require(APP_PATH."views/game/scenes/dialog.php");	
	}

	/**
	* Altera a cor de fundo dos balões de fala
	* @param string $color
	*/
	public function setBalloonColor($color) {
		$this->colors['balloon'] = $color;
	}

	/**
	* Recupera a cor de fundo dos balões de fala
	* @return string
	*/
	public function getBalloonColor() {
		return $this->colors['balloon'];
	}

	/**
	* Altera a cor dos textos
	* @param string $color
	*/
	public function setTextColor($color) {
		$this->colors['text'] = $color;
	}

	/**
	* Recupera a cor dos textos
	* @return string
	*/
	public function getTextColor() {
		return $this->colors['text'];
	}

	/**
	* Altera a cor de fundo do nome do personagem
	* @param string $color
	*/
	public function setBackgroundNameColor($color) {
		$this->colors['backgroundName'] = $color;
	}

	/**
	* Recupera a cor de fundo do nome do personagem
	* @return string
	*/
	public function getBackgroundNameColor() {
		return $this->colors['backgroundName'];
	}

	/**
	* Recupera as cores configuradas
	* @access protected
	* @return array
	*/
	protected function getColors() {
		return $this->colors;
	}

	/**
	* Recupera a questão
	* @return IQuestion
	*/
	public function getQuestion() {
		return $this->question;
	}

	/**
	* Altera a questão
	* @param IQuestion $question
	*/
	public function setQuestion($question) {
		$this->question = $question;
	}	
}