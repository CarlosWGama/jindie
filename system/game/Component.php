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

class Component implements IComponent {

	/**
	* @access protected
	* @var int
	*/
	protected $id;
	
	/**
	* @access procted
	* @var string
	*/
	protected $name;

	/**
	* @access procted
	* @var string
	*/
	protected $description;

	/**
	* @access procted
	* @var array
	*/
	protected $extra = array();
	
	/**
	* Retorna o ID do componente
	* @return int
	*/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Seta o ID do componente
	* @param int $id;
	*/
	public function setID($id) {
	    $this->id = $id;
	}
	
	/**
	* Retorna o nome do componente
	* @return string
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* Seta o nome do componente
	* @param string $name
	*/
	public function setName($name) {
		$this->name = $name;
	}

	/**
	* Retorna a descrição do componente
	* @return string
	*/
	public function getDescription() {
		return $this->description; 
	}

	/**
	* Seta a descrição do componente
	* @param string $description
	*/
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	* Recupera dados/informações Extras do Componente
	* @param string $param
	* @return mix
	*/
	public function getExtra($param) {
		return $this->extra[$param];
	}
	
	/**
	* Seta dados/informações Extras do Componente
	* @param string $param
	* @param mix $value
	*/
	public function setExtra($param, $value) {
		$this->extra[$param] = $value;
	}

	/**
	* Deleta dados/informações Extras do Componente
	* @param string $param
	*/
	public function deleteExtra($param) {
		if (isset($this->extra[$param]))
			unset($this->extra[$param]);
	}
}