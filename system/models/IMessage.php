<?php
/**
*   JIndie
*   @package JIndie
*   @category Model
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*/

namespace JIndie\Model;

interface IMessage {

	/**
	* Remetente
	* @param IUser $user
	*/
	public function setSender($user);	

	/**
	* Remetente
	* @return IUser
	*/
	public function getSender();	

	/**
	* Destinatário
	* @param IUser $user
	*/
	public function setRecipient($user);	

	/**
	* Destinatário
	* @return IUser
	*/
	public function getRecipient();	

	/**
	* Assunto/Título
	* @param string $title
	*/
	public function setTitle($title);	

	/**
	* Assunto/Título
	* @return string
	*/
	public function getTitle();	

	/**
	* Corpo da mensagem
	* @param string $text
	*/
	public function setText($text);	

	/**
	* Corpo da mensagem
	* @return string
	*/
	public function getText();	

	/**
	* Salva a mensagem
	*/
	public function send();

	/**
	* Abre/Carrega os dados da mensagem
	*/
	public function read($messageID);

	/**
	* Marca a mensagem atual como lída
	*/
	public function markAsRead();

	/**
	* Deleta a mensagem atual
	*/
	public function delete();
}