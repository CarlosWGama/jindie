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

interface IUser {

	/**
	* @param int $id
	*/
	public function setID($id);

	/**
	* @return int 
	*/
	public function getID();

	/**
	* @param string $name
	*/
	public function setName($name);

	/**
	* @return string
	*/
	public function getName();

	/**
	* @param string $avatar
	*/
	public function setAvatar($avatar);

	/**
	* @return string 
	*/
	public function getAvatar();
}