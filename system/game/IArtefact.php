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

interface IArtefact  {

	/**
	* Retorna o identificador do Artefato
	* @return int
	*/
	public function getId();
	/**
	* Seta o identificador do Artefato
	* @param int $id
	*/
	public function setId($id);


	/**
	* Retorna o nome do artefato
	* @return string
	*/
	public function getName();
	/**
	* Seta o nome do artefato
	* @param string $name
	*/
	public function setName($name);


	/**
	* Retorna a descrição do artefato
	* @return string
	*/
	public function getDescription();
	/**
	* Seta a descrição do artefato
	* @param string $description
	*/
	public function setDescription($description);


	/**
	* Seta o Status do Artefato como iniciado
	*/
	public function start();
	/**
	* Retorna o Status do Artefato
	* @return mix
	*/
	public function getStatus();
	/**
	* Seta o Status do Artefato
	* @param mix $status
	*/
	public function setStatus($status);
	/**
	* Seta o Status do Artefato como concluído 
	*/
	public function complete();


	/**
	* Recupera a lista de componentes do Artefato
	* @return array of IComponents
	*/
	public function getComponents();
	/**
	* Adicionar um componente ao Artefato
	* @param IComponents $component
	*/
	public function addComponent($component);
	/**
	* Deleta um componente do Artefato
	* @param IComponents $component
	*/
	public function deleteComponent($component);
	/**
	* Deleta todos componentes do artefato
	*/
	public function clearComponents();

}