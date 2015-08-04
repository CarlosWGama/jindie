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

interface IScene {
	
	/**
	* Verifica se os pre-requisitos foram atenditos antes de chamar o showScene
	*/
	public function check();

	/**
	* Gera a Scene
	*/
	public function showScene();

}