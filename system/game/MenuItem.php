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

class MenuItem {

	/**
	* @access protected
	* @var string
	*/
	protected $label;

	/**
	* @access protected
	* @var string
	*/
	protected $link; 

	/**
	* Subitens 
	* @access protected
	* @var array
	*/
	protected $childrenItens = array(); 

	/**
	* Checa se o link deve abrir em nova página (target="_blank")
	* @access protected
	* @var bool
	*/
	protected $newPage = false;

	/**
	* @access public
	* @param string $label
	* @param string $link
	* @param bool $newPage
	* @param array $childrenItens
	*/ 
	public function __construct($label, $link, $newPage = false, $childrenItens = array()) {
		$this->setLabel($label);
		$this->setLink($link);
		$this->setNewPage($newPage);
		$this->setCrildren($childrenItens);
	}

	/**
	* Adiciona um SubItem ao Item atual
	* @access public
	* @param MenuItem $item
	* @param int $position |Define a posição queo item será adicionado. Caso não seja informado, será adicionado no final
	*/ 
	public function addItem($item, $position = null) {
		if (is_a($item, "JIndie\Game\MenuItem") || is_subclass_of($item, "JIndie\Game\MenuItem")) {
			if (is_int($position)) {
				array_splice($this->childrenItens, $position, 0, $item);
				\Log::message(\Language::getMessage('log', 'debug_game_item_subitem_position', array("ITEM" => $this->label, 'position' => $position)), 2);
			}
			else {
				$this->childrenItens[] = $item;
				\Log::message(\Language::getMessage('log', 'debug_game_item_subitem', array("ITEM" => $this->label)), 2);
			}
		} else 
			throw new Exception(\Language::getMessage('error', 'game_not_menu_item'), 21);
	}

	/**
	* @access public 
	* @param string $label
	* @param string $link
	* @param bool $newPage
	*/
	public function createSubItem($label, $link, $newPage = false) {
		if (file_exists(GAME_PATH.'MenuItem.php')) {
			require_once(GAME_PATH.'MenuItem.php');
			$menu = new \MenuItem($label, $link, $newPage);

			\Log::message(\Language::getMessage('log', 'debug_game_item_create_subitem', array("ITEM" => $this->label, "FROM" => "\MenuItem", "LABEL" => $label, "LINK" => $link, "NEW_PAGE" => $newPage, "RETURN_ITEM" => 0)), 2);
		} else {
			require_once(GAME_JI_PATH.'MenuItem.php');
			$menu = new MenuItem($label, $link, $newPage);

			\Log::message(\Language::getMessage('log', 'debug_game_item_create_subitem', array("ITEM" => $this->label, "FROM" => "JIndie\Game\MenuItem", "LABEL" => $label, "LINK" => $link, "NEW_PAGE" => $newPage, "RETURN_ITEM" => 0)), 2);
		}
		
		$this->childrenItens[] = $menu;
	}

	/**
	* @access public 
	* @param int $index
	*/
	public function removeItem($index) {
		if (is_int($position) && isset($this->childrenItens[$position]))  {
			unset($this->childrenItens[$position]);
			$this->childrenItens = array_values($this->childrenItens);

			\Log::message(\Language::getMessage('log', 'debug_game_item_remove_subitem', array("ITEM" => $this->label, "INDEX" => $index, 'LABEL' => $label)), 2);
		}
	}

	/**
	* Remove todos os subitens
	* @access public
	*/
	public function clearChildren() {
		$this->childrenItens = array();
	}

	/**
	* @access public
	* @param string $label
	*/
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	* @access public
	* @return string
	*/
	public function getLabel() {
		return $this->label;
	}

	/**
	* @access public
	* @param string $link
	*/
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	* @access public
	* @return string
	*/
	public function getLink() {
		return $this->link;
	}

	/**
	* @access public
	* @param array $childrenItens
	*/
	public function setCrildren($childrenItens) {
		$this->childrenItens = (array)$childrenItens;
	}

	/**
	* @access public
	* @return array
	*/
	public function getChildren() {
		return $this->childrenItens;
	}

	/**
	* @access public
	* @param bool $newPage
	*/
	public function setNewPage($newPage) {
		$this->newPage = (bool)$newPage;
	}

	/**
	* @access public
	* @return bool
	*/
	public function getNewPage() {
		return $this->newPage;
	}
}