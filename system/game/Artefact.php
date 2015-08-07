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

class Artefact implements IArtefact {

	/**
	* Identificador do Artefato
	* @access protected
	* @var int|string
	*/
	protected $id;

	/**
	* Nome dado ao artefato
	* @access protected
	* @var string
	*/
	protected $name;

	/**
	* Descrição ou texto relacionado ao artefato
	* @access protected
	* @var string
	*/
	protected $description;

	/**
	* Status do artefato como concluído ou iniciado
	* @access protected
	* @var mix
	*/
	protected $status = 0;

	/**
	* Lista de componentes do artefato
	* @access protected
	* @var array
	*/
	protected $components = array();
	
	/**
	* Retorna o identificador do Artefato
	* @return int
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* Seta o identificador do Artefato
	* @param int $id
	*/
	public function setId($id) {
		$this->id = $id;
	}

	/**
	* Retorna o nome do artefato
	* @return string
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* Seta o nome do artefato
	* @param string $name
	*/
	public function setName($name) {
		$this->name = $name;
	}

	/**
	* Retorna a descrição do artefato
	* @return string
	*/
	public function getDescription() {
		return $this->description;
	}

	/**
	* Seta a descrição do artefato
	* @param string $description
	*/
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	* Seta o Status do Artefato como iniciado (0)
	*/
	public function start() {
		$this->status = 0;
	}

	/**
	* Retorna o Status do Artefato
	* @return int
	*/
	public function getStatus() {
		return $this->status;
	}

	/**
	* Seta o Status do Artefato
	* @param int $status
	*/
	public function setStatus($status) {
		$this->status = intval($status);
	}

	/**
	* Seta o Status do Artefato como concluído (1)
	*/
	public function complete() {
		$this->status = 1;
	}

	/**
	* Recupera a lista de componentes do Artefato
	* @return array of IComponents
	*/
	public function getComponents() {
		return $this->components;
	}

	/**
	* Adicionar um componente ao Artefato
	* @param IComponents $component
	*/
	public function addComponent($component) {
		if ($component instanceof IComponent) {
			\Log::message(\Language::getMessage('log', 'debug_game_component'), 2);
			$this->components[] = $component;
		}
		else {
			$msg = \Language::getMessage('error', 'game_not_component');
			\Log::message($msg, 2);
			throw new \Exception($msg, 42);
		}		
	}

	/**
	* Deleta um componente do Artefato
	* @param IComponents $component
	*/
	public function deleteComponent($component) {
		foreach ($this->components as $key => $value) {
			if ($value === $component) {
				unset($this->components[$key]);
				break;
			}
		}
	}

	/**
	* Deleta todos componentes do artefato
	*/
	public function clearComponents() {
		$this->components = array();
	}

}