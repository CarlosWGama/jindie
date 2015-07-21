<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Components 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;

class Goal {
	
	/**
	* @access protected
	* @var string
	*/
	protected $description;

	/**
	* @access protected
	* @var array
	*/
	protected $steps = array();

	/**
	* @access public
	* @param string $description
	*/
	public function __construct($description = '') {
		$this->description = $description;
	}

	/**
	* @access public
	* @param string $description
	*/
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	* @access public
	* @return string 
	*/
	public function getDescription() {
		return $this->description;
	}

	/**
	* @access public
	* @param string $step
	* @param bool $accomplished
	* @param int $position
	*/
	public function addStep($step, $accomplished = false, $position = null) {

		$step = array (
			'step'				=> $step,
			'accomplished'		=> $accomplished
		);
		
		if (is_int($position)) 
			array_splice($this->steps, $position, 0, array($step));
		else
			$this->steps[] = $step;
	}

	/**
	* @access public
	* @return array
	*/
	public function getSteps() {
		return $this->steps;
	}

	/**
	* @access public
	* @return int|string $index 
	*/
	public function completeStep($index) {
		if (is_int($index) && isset($this->steps[$index])) 
			$this->steps[$index]['accomplished'] = true;
		else {
			foreach ($this->steps as $key => $step)  {
				if ($step['step'] == $index) {
					$this->steps[$key]['accomplished'] = true;
					break;
				}
			}
		}
	}

	/**
	* @access public
	* @return int|string $index 
	*/
	public function removeStep($index) {
		if (is_int($index) && isset($this->steps[$index])) 
			unset($this->steps[$index]);
		else {
			foreach ($this->steps as $key => $step)  {
				if ($step['step'] == $index) {
					unset($this->steps[$key]);
					break;
				}
			}
		}
		$this->steps = array_values($this->steps);
	}

	/**
	* @access public
	*/
	public function clearSteps() {
		$this->steps = array();
	}

	/**
	* @access public
	* @return double
	*/
	public function getPercentage() {
		$total = count($this->steps);
		$accomplished = 0;

		foreach ($this->steps as $key => $step)  
			if ($step['accomplished'] === true)
				$accomplished++;

		return number_format((($accomplished * 100) / $total), 2, '.', '');
	}
}