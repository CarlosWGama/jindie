<?php
/**
* 	JIndie
*	@package JIndie
*	@category Model
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Model;

interface IMailBox {

	/**
	* Retorna todas as mensagens do usuário
	* @return array of IMessage
	*/
	public function listMessage();

	/**
	* Envia a mensagem para o outro jogador/Salva no banco
	* @param IMessage $message
	* @return bool
	*/
	public function sendMessage($message);

	/**
	* Abre a mensagem com ID passado
	* @param int $id
	* @return IMessage
	*/
	public function openMessage($id);

	/**
	* Deleta a mensagem com o id passado
	* @param int $id
	* @return bool
	*/
	public function deleteMessage($id);

	/**
	* Responde a mensagem com ID informado
	* @param int $id
	* @param IMessage $message
	* @return bool
	*/
	public function replyMessage($id, $message);
	
}