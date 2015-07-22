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

require_once(GAME_JI_PATH.'MenuItem.php');

class Menu {

	/**
	* @access protected
	* @var array
	*/
	protected $itens = array();

	/**
	* @access protected
	* @var string
	*/
	protected $class = "menu-class";

	/**
	* @access protected
	* @var string
	*/
	protected $id 	= "menu-id";

	/**
	* @access public
	* @param array $itens
	*/
	public function __construct($itens = array()) {
		$this->itens = $itens;
	}

	/**
	* Adiciona um MenuItem ao objeto Menu
	* @access public
	* @param MenuItem $item
	* @param int $position 	| Define a posição a qual será adicionado. Senão for definido, entrará no final
	*/
	public function addItem($item, $position = null) {
		if (is_a($item, "JIndie\Game\MenuItem") || is_subclass_of($item, "JIndie\Game\MenuItem")) {
			if (is_int($position)) 
				array_splice($this->itens, $position, 0, $item);
			else
				$this->itens[] = $item;
		}
	}

	/**
	* Cria um MenuItem
	* @access public
	* @uses $this->game->getMenu()->createItem('Home', '/home'); 
	* @uses $item =  $this->game->getMenu()->createItem('Home', '/home', false, true);
	* @param string $label
	* @param string $link
	* @param bool $newPage | Se abrirá em uma nova página ou na atual
	* @param bool $returnItem | Retorna o item (true) ou o adiciona no final (false)
	* @return MenuItem
	*/
	public function createItem($label, $link, $newPage = false, $returnItem = false) {
		if (file_exists(GAME_PATH.'MenuItem.php')) {
			require_once(GAME_PATH.'MenuItem.php');
			$menu = new \MenuItem($label, $link, $newPage);
		} else {
			$menu = new MenuItem($label, $link, $newPage);
		}

		if ($returnItem == true)
			return $menu;

		$this->addItem($menu);
	}

	/**
	* @access public
	* @param bool $returnHTML
	* @return string | Apenas se returnHTML == true
	*/
	public function showMenu($returnHTML = false) {
		$menu = $this->buildMenu($this->itens);
		$menuClass = $this->class;
		$menuID = $this->id;

		if ($returnHTML) {
			ob_start();
			include(APP_PATH.'views/game/Menu.php');
			$menu = ob_get_clean();
			return $menu;
		} else 
			include(APP_PATH.'views/game/Menu.php');
	}

	/**
	* @access protected
	* @param MenuItem $itens
	* @return string
	*/
	protected function buildMenu($itens) {
		$menu = "<ul>\n";
		foreach ($itens as $item) {

			$menu .= "<li><a href=\"" . $item->getLink() . "\" " . ($item->getNewPage() ? 'target="_blank"' : '') . ">" . $item->getLabel() . "</a>";

			$subItens = $item->getChildren();
		
			if (!empty($subItens)) {
				$menu .= "\n";
				$menu .= $this->buildMenu($subItens);
			}

			$menu .= "</li>\n";
		}
		$menu .= "</ul> \n";
		return $menu;
	}

	/**
	* Remove todos os itens do menu
	* @access public
	*/
	public function clearItens() {
		$this->itens = array();
	}

	/**
	* @access public 
	* @param int $index
	*/
	public function removeItem($index) {
		if (is_int($position) && isset($this->itens[$position]))  {
			unset($this->itens[$position]);
			$this->itens = array_values($this->itens);
		}
	}

	/**
	* @access public
	* @param array $itens
	*/
	public function setItens($itens) {
		$this->itens = (array)$itens;
	}

	/**
	* @access public
	* @return array
	*/
	public function getItens() {
		return $this->itens;
	}

	/**
	* @access public
	* @param istring $id
	*/
	public function setID($id) {
		$this->id = $id;
	}

	/**
	* @access public
	* @return string
	*/
	public  function getID() {
		return $this->id;
	}

	/**
	* @access public
	* @param string $class
	*/
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	* @access public
	* @return string
	*/
	public function getClass() {
		return $this->class;
	}
}