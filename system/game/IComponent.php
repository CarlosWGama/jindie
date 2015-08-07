<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Components of Game 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

interface IComponent {
	
	/**
	* Retorna o nome do componente
	* @return string
	*/
	public function getName();
	/**
	* Seta o nome do componente
	* @param string $name
	*/
	public function setName($name);


	/**
	* Retorna a descrição do componente
	* @return string
	*/
	public function getDescription();
	/**
	* Seta a descrição do componente
	* @param string $description
	*/
	public function setDescription($description);


	/**
	* Recupera dados/informações Extras do Componente
	* @param string $param
	* @return mix
	*/
	public function getExtra($param);
	/**
	* Seta dados/informações Extras do Componente
	* @param string $param
	* @param mix $value
	*/
	public function setExtra($param, $value);
	/**
	* Deleta dados/informações Extras do Componente
	* @param string $param
	*/
	public function deleteExtra($param);

}