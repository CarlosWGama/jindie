<?php
/**
*   JIndie
*   @package JIndie
*   @subpackage Code
*   @category Library
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*/

namespace JIndie\Code;

interface ICode {

	/**
	* Tempo máximo de Execução do Script
	* @return int (segundos)
	*/
	public function maxTimeExecution();
	
	/**
	* String responsável pela separação dos comandos
	* @return string
	*/
	public function getBreakLine();

	/**
	* Recupera a lista de comandos configuradas
	* @return array
	*/
	public function getCommands();

	/**
	* Verficia se o script é Case Sensitive
	* @return bool
	*/
	public function isCaseSensitive();

	/**
	* Recupera String responsável pelo operador AND
	* @return string
	*/
	public function getAND();

	/**
	* Recupera String responsável pelo operador OR
	* @return string
	*/
	public function getOR();

	/**
	* Recupera String responsável pela estrutura do inicio do if. Usa tag [CONDITION] onde fica a condição que será executada
	* @return string
	*/
	public function getIfStructure();

	/**
	* Recupera String responsável pela estrutura do inicio do else
	* @return string
	*/
	public function getElseStructure();

	/**
	* Recupera String responsável pela estrutura do fim do if
	* @return string
	*/
	public function getEndIfStructure();

	/**
	* Recupera String responsável pela estrutura do inicio do While. Usa tag [CONDITION] onde fica a condição que será executada
	* @return string
	*/
	public function getWhileStructure();

	/**
	* Recupera String responsável pela estrutura do fim do While
	* @return string
	*/
	public function getEndWhileStructure();
}