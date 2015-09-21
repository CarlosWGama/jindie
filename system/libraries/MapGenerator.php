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

class MapGenerator {

	/**
	* Tamanho das imagens no mapa
	* @access protected
	* @var int
	*/
	protected $imageSize = 32;
	
	/**
	* Tamanho do mapa em X,Y
	* @access protected
	* @var array|Vector
	*/
	protected $mapSize = array('x' => 5, 'y' => 5);

	/**
	* A posição atual do ponteiro na adição dos tiles
	* @access protected
	* @var array|Vector
	*/
	protected $currentPosition = array('x' => 1, 'y' => 1);

	/**
	* Lista de tiles
	* @access protected
	* @var array|Tiles
	*/
	protected $tiles = array();

	/**
	* Tile padrão a ser inserido quando não houve nenhum tile na posição
	* @access protected
	* @var array|Tile
	*/
	protected $defaultTile = array('field' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAFElEQVR42mNI687+TwxmGFVIX4UAWFTrjQunmzcAAAAASUVORK5CYII=');

	/**
	* Usuário pode clicar caso haja URL configurada
	* @access protected
	* @var bool
	*/
	protected $clickable = true;
	/**
	* @param int $size
	*/
	public function setImageSize($size) {
		if (filter_var($size, FILTER_VALIDATE_INT) && $size > 0) {
			$this->imageSize = $size;

			$msg = Language::getMessage('log', 'debug_map_new_image_size', array('size' => $size));
			Log::message($msg, 2);
		}
		else {
			$msg = Language::getMessage('map', 'error_int_val');
			Log::message($msg, 2);
			throw new Exception($msg, 23);			
		}
	}

	/**
	* Define os tile padrão
	* @uses $this->mapGenerator->setDefaultTile('link-field', 'link-object', 'link-field');
	* @uses $this->mapGenerator->setDefaultTile(array('field' => 'link-field', 'object' => 'link-object', 'url' => 'link-field'));
	* @param string|array $field 
	* @param string $object
	* @param string $url
	*/
	public function setDefaultTile($field, $object = null, $url = null) {
		$this->defaultTile = array('field' => "", 'object' => "", 'url' => "");

		if (is_array($field)) {
			extract($field);
		}
		
		if (!is_null($field)) $this->defaultTile['field'] = $field;
		if (!is_null($object)) $this->defaultTile['object'] = $object;
		if (!is_null($url)) $this->defaultTile['url'] = $url;

		$msg = Language::getMessage('log', 'debug_map_new_default_tile', array('tile' => json_encode($this->defaultTile)));
		Log::message($msg, 2);
	}

	/**
	* Define o tamanho do mapa
	* @uses $this->mapGenerator->setMapSize(array(10, 10));
	* @uses $this->mapGenerator->setMapSize(10, 10);
	* @param array|int $vector
	* @param int $y
	*/
	public function setMapSize($vector, $y = null) {
		if (!is_null($y))
			$vector = array($vector, $y);


		$this->validVector($vector);
		$vector = $this->formatVector($vector);

		$this->resetMap();
		$this->mapSize = $vector;

		$msg = Language::getMessage('log', 'debug_map_new_map_size', array('map' => '('.$this->mapSize['x'].','.$this->mapSize['y'].')'));
		Log::message($msg, 2);
	}

	/**
	* Retorna o tamanho do Mapa Definido
	* @return array|Vector(X,Y)
	*/
	public function getMapSize() {
		return $this->mapSize;
	}

	/**
	* Zera todas as informações relacionada ao mapa atual
	*/
	public function resetMap() {
		$this->tiles = array();
		$this->mapSize = null;
		$this->currentPosition = array('x' => 1, 'y' => 1);

		$msg = Language::getMessage('log', 'debug_map_reset_map');
		Log::message($msg, 2);
	}

	/**
	* Adiciona um novo tile na posição atual do ponteiro e avança o ponteiro para a próxima posição válida
	* @uses $this->mapGenerator->addTile('link-field', 'link-object', 'link-field');
	* @uses $this->mapGenerator->addTile(array('field' => 'link-field', 'object' => 'link-object', 'url' => 'link-field'));
	* @param string|array $field 
	* @param string $object
	* @param string $url
	*/
	public function addTile($field, $object = null, $url = null) {
		//Add by Array
		if (is_array($field)) {

			$f = null;
			$o = null;
			$u = null;
			//By Name
			if (isset($field['field']) || isset($field['object']) || isset($field['url'])) {
 				if (isset($field['field'])) 	$f = $field['field'];
 				if (isset($field['object'])) 	$o = $field['object'];
 				if (isset($field['url'])) 		$u = $field['url'];
			} 
			//By Position
			elseif (isset($field[0]) || isset($field[1]) || isset($field[2])) {
 				if (isset($field[0])) 	$f = $field[0];
 				if (isset($field[1])) 	$o = $field[1];
 				if (isset($field[2])) 	$u = $field[2];	
			}

			$this->addTile($f, $o, $u);
		} else {
			//Add on currentPosition
			list($x, $y) = array_values($this->currentPosition);

			$this->tiles[$x][$y] = array(
				'field' 		=> $field,
				'object'		=> $object,
				'url'			=> $url
			);

			$msg = Language::getMessage('log', 'debug_map_new_tile', array('position' => '(' . $x . ',' . $y . ')'));
			Log::message($msg, 2);

			//Change vector to new position
			if (array_diff_assoc($this->currentPosition, $this->mapSize)) {
				$x++;

				if ($x > $this->mapSize['x']) {
					$x = 1;
					$y++;
				}

				$this->currentPosition = array('x' => $x, 'y' => $y);

				$msg = Language::getMessage('log', 'debug_map_current_position', array('position' => '(' . $this->currentPosition['x'] . ',' . $this->currentPosition['y'] . ')'));
				Log::message($msg, 2);
			}
		}
	}

	/**
	* Adiciona um novo tile numa posição determinada
	* @uses $this->mapGenerator->addTileAtPosition(array(10, 10), 'link-field', 'link-object', 'link-field');
	* @uses $this->mapGenerator->addTileAtPosition(array(10, 10), array('field' => 'link-field', 'object' => 'link-object', 'url' => 'link-field'));
	* @param array $position 
	* @param string|array $field 
	* @param string $object
	* @param string $url
	*/
	public function addTileAtPosition($position, $field, $object = null, $urlToAction = null) {
		//Check
		$this->validVector($position);
		$vector = $this->formatVector($position);
		
		//Save old positions
		list($x, $y) = array_values($this->currentPosition);

		//Change Position
		$this->currentPosition =  $vector;

		//Save Tile
		$this->addTile($field, $object, $urlToAction);

		//Return to old position
		$this->currentPosition = array($x, $y);
	}

	/**
	* Altera o valor de um tile
	* @uses $this->mapGenerator->alterTile(array(10, 10), 'object', 'new-link');
	* @uses $this->mapGenerator->alterTile(array(10, 10), array('object' => 'new-link', 'url' => 'new-url'));
	* @param array $position
	* @param array|string $param
	* @param string $value
	*/
	public function alterTile($position, $param, $value = null) {
		$this->validVector($position, true, true);	
		$vector = $this->formatVector($position);

		//Array
		if (is_array($param)) {
			foreach ($param as $key => $value)
				$this->alterTile($vector, $key, $value);
		} else {

			if (isset($this->tiles[$vector['x']][$vector['y']])) {
				if (in_array($param, array('object', 'field', 'url'))) {
					$this->tiles[$vector['x']][$vector['y']][$param] = $value;

					$msg = Language::getMessage('log', 'debug_map_alter_value', array('position' => '('.$vector['x'].','.$vector['y'].')', 'param' => $param, 'value' => $value));
					Log::message($msg, 2);
				}
				else {
					$msg = Language::getMessage('map', 'error_param_not_exists', array('param' => $param));
					Log::message($msg, 2);
					throw new Exception($msg, 24);
				}
			}
			else {
				$msg = Language::getMessage('map', 'tile_not_exists', array('position' => $vector['x'].','.$vector['y']));
				Log::message($msg, 2);
				throw new Exception($msg, 25);
			}
		}
	}

	/**
	* Retorna um tile pela sua posição
	* @uses $this->mapGenerator->getTile(array(10, 10));
	* @uses $this->mapGenerator->getTile(10, 10);
	* @param int|array $position
	* @param int $int
	* @return array|Tile
	*/
	public function getTile($position, $y = null) {
		if (!is_null($y))
			$position = array($position, $y);

		$this->validVector($position, true, true);
		$vector = $this->formatVector($position);		
		return $this->tiles[$vector['x']][$vector['y']];
	}

	/**
	* Insere uma lista de $tiles organizados nas posição X,Y
	* @param array $tiles
	*/
	public function setMap($tiles) {
		if (!is_array($tiles)) {
			$msg = Language::getMessage('map', 'not_array');
			Log::message($msg, 2);
			throw new Exception($msg, 26);
		}

		$newTiles = array();
		$maxValueX = 0;
		$maxValueY = 0;
		foreach ($tiles as $x => $row) {

			if (!filter_var($x, FILTER_VALIDATE_INT) && $x > 0) {
				$msg = Language::getMessage('map', 'error_position');
				Log::message($msg, 2);
				throw new Exception($msg, 27);
			}
			
			if (!is_array($row)) {
				$msg = Language::getMessage('map', 'error_map_structure');
				Log::message($msg, 2);
				throw new Exception($msg, 28);
			}

			if ($maxValueX < $x)
				$maxValueX = $x;

			foreach ($row as $y => $tile) {

				if (!filter_var($y, FILTER_VALIDATE_INT) && $y > 0) {
					$msg = Language::getMessage('map', 'error_position');
					Log::message($msg, 2);
					throw new Exception($msg, 27);
				}

				if (!is_array($tile)) {
					$msg = Language::getMessage('map', 'error_tile_structure', array('position' => $x.','.$y));
					Log::message($msg, 2);
					throw new Exception($msg, 29);
				}
				
	
				if ($maxValueY < $y)
					$maxValueY = $y;				

				$newTiles[$x][$y] = $tile;
			}
		}

		$this->setMapSize($maxValueX, $maxValueY);
		$this->tiles = $newTiles;

		$msg = Language::getMessage('log', 'debug_map_set_map');
		Log::message($msg, 2);
	}

	public function getMap($full = false) {
		if (!$full) 
			return $this->tiles;

		//Com o Default
		$tiles = array();
		
		for ($x = 1; $x <= $this->mapSize['x']; $x++) {
			for ($y = 1; $y <= $this->mapSize['y']; $y++) {
				if (isset($this->tiles[$x][$y]))
					$tiles[$x][$y] = $this->tiles[$x][$y];
				else 
					$tiles[$x][$y] = $this->defaultTile;
			}
		}
		return $tiles;
	}

	/**
	* Gera o mapa
	* @param bool $returnHTML
	* @return string |Caso returnHTML = true
	*/
	public function generate($returnHTML = true) {

		$msg = Language::getMessage('log', 'debug_map_generate');
		Log::message($msg, 2);

		//
		$imageSize = $this->imageSize;
		$map['tiles'] = $this->getMap(true);
		$map['clickable'] = $this->isClickable();
		$map = json_encode($map);


		if ($returnHTML) {
			ob_start();
			include(APP_PATH.'views/library/map.php');
			$map = ob_get_clean();
			return $map;
		} else 
			include(APP_PATH.'views/library/map.php');
	}

	/**
	* Gera imagens de cores solidas
	* @param string $color
	* @return string 
	*/
	public function getColor($color) {
		switch ($color) {
			case "green":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAFElEQVR42mNI687+TwxmGFVIX4UAWFTrjQunmzcAAAAASUVORK5CYII=";
				break;
			case "red":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAABPSURBVChTY/zp7Pyf+d49BgZWFgas4Pcfhr9KSgwMfxQV/v9nYMCLQWqY/rOyYjcJSRSkhomgKqiCIaGQ8fdvgv4BqWGEBfh/HAHOCA1wAJO6KnWcSPzpAAAAAElFTkSuQmCC";
				break;
			case "blue":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAIAAAF1V2h8AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD9JREFUeNpilA1axMDAwMQABgABxADkgZgAAcQIEQUCgABiRJGHUgABhJBGEYYDgAAiJE8qHyDA0M0joJye0gDR7geYDW9lNQAAAABJRU5ErkJggg==";
				break;
			case "white":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAB90RVh0U29mdHdhcmUATWFjcm9tZWRpYSBGaXJld29ya3MgOLVo0ngAAAAWdEVYdENyZWF0aW9uIFRpbWUAMTAvMDMvMTNA0CjrAAAAGElEQVQYlWP8//+/PAMRgIkYRaMKqacQAAZLAzDnLBG8AAAAAElFTkSuQmCC";
				break;
			case "gray":
			case "grey":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAFQPDegAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABlJREFUGNNjYFADQhgBYyJEkAk16jNpKggAKCIO2TdVg/oAAAAASUVORK5CYII=";
				break;
			case "yellow":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAWdEVYdENyZWF0aW9uIFRpbWUAMDMvMjgvMTSON3MqAAAAGElEQVQYlWP8/5/hPwMRgIkYRaMKqacQANQTAxHwovCxAAAAAElFTkSuQmCC";
				break;
			case "pink":
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKAQMAAAC3/F3+AAAABGdBTUEAALGPC/xhBQAAAAZQTFRF+AV7AAAAKHiKVgAAAAtJREFUGNNjYMAHAAAeAAHJmCODAAAAAElFTkSuQmCC";
				break;
			case "black":
			default:
				return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKEAIAAABSwISpAAAACXBIWXMAAABIAAAASABGyWs+AAAACXZwQWcAAAAKAAAACgBOpnblAAAAD0lEQVQoz2NgGAWjgJYAAAJiAAEQ3MCgAAAAJXRFWHRjcmVhdGUtZGF0ZQAyMDA5LTA3LTA4VDE5OjE1OjMyKzAyOjAwm1PZQQAAACV0RVh0bW9kaWZ5LWRhdGUAMjAwOS0wNy0wOFQxOToxNTozMiswMjowMMTir3UAAAAASUVORK5CYII=";
				break;
		}
	}

	/**
	* @param bool clickable
	*/
	public function setClickable($clickable) {
		$this->clickable = (bool) $clickable;
	}

	/**
	* @return bool
	*/
	public function isClickable() {
		return $this->clickable;
	}

	/**
	* Realiza validações no Vector(x, y)
	* @access protected
	* @param array $vector
	* @param bool $zero |Caso true, o vector não pode ter posições X < 1 e Y < 1
	* @param bool $isset |Caso true, é preciso ter um tile na posição informada do vector
	*/
	protected function validVector($vector, $zero = false, $isset = false) {
		if (!is_array($vector)) {
			$msg = Language::getMessage('map', 'not_array');
			Log::message($msg, 2);
			throw new Exception($msg, 26);
		}

		if (count($vector) != 2) {
			$msg = Language::getMessage('map', 'error_count_param_vector');
			Log::message($msg, 2);
			throw new Exception($msg, 29);
		}

		//format
		$vector = $this->formatVector($vector);

		if (!filter_var($vector['x'], FILTER_VALIDATE_INT) || !filter_var($vector['y'], FILTER_VALIDATE_INT)) {
			$msg = Language::getMessage('map', 'error_position');
			Log::message($msg, 2);
			throw new Exception($msg, 27);
		}

		if ($zero) {
			if (($vector['x'] < 1) || ($vector['y'] < 1)) {
				$msg = Language::getMessage('map', 'error_position_zero');
				Log::message($msg, 2);
				throw new Exception($msg, 31);
			}
		}

		if ($isset) {
			if (!$this->hasTile($vector)) {
				$msg = Language::getMessage('map', 'tile_not_exists', array('position' => $vector['x'].','.$vector['y']));
				Log::message($msg, 2);
				throw new Exception($msg, 25);
			}
		}
	}

	/**
	* @access protected
	* @param array $vector
	* @return array
	*/
	protected function formatVector($vector) {
		if (!isset($vector['x']) || isset($vector['y'])) {
			$vector = array('x' => current($vector), 'y' => end($vector));
		}

		return $vector;
	}

	/**
	* @return array
	*/
	public function getDefaultTile() {
		return $this->defaultTile;
	}

	public function hasTile($vector, $y = null) {
		if (!is_null($y))
			$vector = array($vector, $y);
		
		try {
			$vector = $this->formatVector($vector, true);
			return isset($this->tiles[$vector['x']][$vector['y']]);	
		} catch (Exception $ex) {
			return false;
		}

		
	}
}