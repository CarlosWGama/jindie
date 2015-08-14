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

class SceneText implements IScene {

	/**
	* @access protected
	* @var string
	*/
	protected $text;

	/**
	* @access protected
	* @var string
	*/
	protected $image;

	/**
	* @return string
	*/
	public function getText() {
		return $this->text;
	}

	/**
	* @param string $text
	*/
	public function setText($text) {
		$this->text = $text;
	}

	/**
	* @return string
	*/
	public function getImage() {
		return $this->image;
	}

	/**
	* @param string $text
	*/
	public function setImage($image) {
		$this->image = $image;
	}
	
	/**
	* Realiza a validação da Scene
	*/
	public function check() {
		\Log::message(\Language::getMessage('log', 'debug_scene_start_check', array('scene' => "SceneText")), 2);

		if (empty($this->text)) 
			throw new \Exception(\Language::getMessage('scenes', 'scenedtext_no_text'), 43);	

		\Log::message(\Language::getMessage('log', 'debug_scene_end_check', array('scene' => "SceneText")), 2);
	}

	/**
	* Exibe o conteúdo da Scene
	* @return string
	*/
	public function showScene() {
		\Log::message(\Language::getMessage('log', 'debug_scene_show_scene', array('scene' => "SceneText")), 2);

		$text = $this->getText();
		$image = $this->getImage();

		require(APP_PATH."views/game/scenes/text.php");	
	}



}