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

class Tile implements IComponent {

	/**
	* Nome do componente
	* @access protected
	* @var string
	*/
	protected $name;

	/**
	* Informação/Descrição/Texto relacionado ao componente
	* @access protected
	* @var string
	*/
	protected $description;

	/**
	* Informações do Tile do componente
	* @access protected
	* @var array|Tile
	*/
	protected $tile = array('field' => "", 'object' => "", 'url' => "");

	/**
	* Informações extras relacionada ao Componente
	* @access protected
	* @var array
	*/
	protected $extra = array();

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
		return (isset($this->extra[$param]) ? $this->extra[$param] : null);
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

	/**
	* Informa dados do Tile
	* @uses $tile->seTile('assets/field.jpg', 'assets/field.png', 'controller/url-destino');
	* @uses $tile->seTile(array('field' => 'assets/field.jpg', 'object' => 'assets/field.png', 'url' => 'controller/url-destino'));
	* @param array|string $field
	* @param string $object
	* @param string $url
	*/
	public function setTile($field, $object = null, $url = null) {
		if (is_array($field)) {
			$object = (isset($field['object']) ? $field['object'] : null);
			$url = (isset($field['url']) ? $field['url'] : null);
			$field = (isset($field['field']) ? $field['field'] : null);
		}

		$this->tile = array(
			'field'		=> $field,
			'object'	=> $object,
			'url'		=> $url,
		);
	}

	/**
	* Recupera os dados do tile
	* @return array
	*/
	public function getTile() {
		return $this->tile;
	}

	/**
	* Seta informação sobre o campo field
	* @param string $field
	*/
	public function setField($field) {
		$this->tile['field'] = $field;
	}
	/**
	* Recupera informação sobre o campo field
	* @return string
	*/
	public function getField() {
		return $this->tile['field'];
	}

	/**
	* Seta informação sobre o campo object
	* @param string $object
	*/
	public function setObject($object) {
		$this->tile['object'] = $object;
	}
	/**
	* Recupera informação sobre o campo object
	* @return string
	*/
	public function getObject() {
		return $this->tile['object'];
	}

	/**
	* Seta informação sobre o campo url
	* @param string $url
	*/
	public function setUrl($url) {
		$this->tile['url'] = $url;
	}
	/**
	* Recupera informação sobre o campo url
	* @return string
	*/
	public function getUrl() {
		return $this->url;
	}
}