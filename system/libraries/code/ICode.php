<?php
/**
*   JIndie
*   @package JIndie
*   @category Library
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*/

namespace JIndie\Code;

interface ICode {
	
	public function getBreakLine();

	public function getCommands();

	public function getCaseSensitive();

	public function getAND();

	public function getOR();

	public function getLeftParen();

	public function getRightParen();

	public function getIfStructure();

	public function getElseStructure();

	public function getEndIfStructure();

	public function getWhileStructure();

	public function getEndWhileStructure();
}