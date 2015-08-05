<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Question
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

interface IQuestion {
	
	/**
	* Recuper ao Identificador do tipo da Questão (1 => Aberta (ShortAnswer), 2 => Fechada (MultipleChoice) )
	* @return int
	*/
	public function getTypeQuestion();

	/**
	* Recupera o enunciado da questão
	* @return string
	*/
	public function getQuestion();

	/**
	* Insere o enunciado da questão
	* @param string $question
	*/
	public function setQuestion($question);

	/**
	* @param string $answer
	* @return bool
	*/
	public function checkAnswer($answer);

	/**
	* URL para onde enviará a resposta do usuário
	* @return string
	*/
	public function getURLToSubmit();
	
	/**
	* Gera um HTML com a questão estruturada
	* @param bool $returnHTML
	* @return string
	*/
	public function showQuestion($returnHTML = false);
}