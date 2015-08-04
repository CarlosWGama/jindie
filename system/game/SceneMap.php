<?php
/**
* 	JIndie
*	@package JIndie
*	@subpackage Game
*	@category Scene 
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

namespace JIndie\Game;
use JIndie\Code\CodeReaderException;

class SceneMap implements IScene {


	protected $navegation = 'PointAndClick';

	protected $player;

	protected $playerPosition;

	protected $map;

	protected $code;

	protected $urlSubmitCode = '';

	protected $session;

	public function __construct() {

		//Map
		if (file_exists(LIBRARIES_PATH.'MapGenerator.php')) 
			require_once(LIBRARIES_PATH.'MapGenerator.php');
		else 
			require_once(LIBRARIES_JI_PATH.'MapGenerator.php');

		$this->map = new \MapGenerator();

		//CodeReader
		if (file_exists(LIBRARIES_PATH.'CodeReader.php')) 
			require_once(LIBRARIES_PATH.'CodeReader.php');
		else 
			require_once(LIBRARIES_JI_PATH.'CodeReader.php');

		$this->code = new \CodeReader();
		$this->code->setRules('navegation');

		//Session
		require_once(LIBRARIES_JI_PATH.'Session.php');
		$this->session = new \Session();
	}

	public function showScene() {

		if ($this->navegation == "PointAndClick") {
			$this->map->setClickable(true);
			$hasCode = false;
		} else {
			if ($this->map->hasTile($this->playerPosition)) {
				$this->map->alterTile($this->playerPosition, 'object', $this->player);	
			} else {
				$tile = $this->map->getDefaultTile();
				$tile['object'] = $this->player;
				$this->map->addTileAtPosition($this->playerPosition, $tile);	
			}
			
			$this->map->setClickable(false);
			$hasCode = true;
			$urlSubmitCode = $this->getUrlCode();
		}

		$map = $this->map->generate(true);

		require(APP_PATH."views/game/scenes/map.php");

	}

	public function check () {

		if (empty($this->map->getMap(true)))
			throw new \Exception(\Language::getMessage("scenes", "scenemap_no_map"));

		switch ($this->navegation) {
			case "Code":
				if (empty($this->player))
					throw new \Exception(\Language::getMessage("scenes", "scenemap_no_player"));

				if (!is_array($this->playerPosition) || count($this->playerPosition) != 2) 
					throw new \Exception(\Language::getMessage("scenes", "scenemap_position_no_vector"));
				else {
					$this->playerPosition = array('x' => current($this->playerPosition), 'y' => end($this->playerPosition));

					if (!(filter_var($this->playerPosition['x'], FILTER_VALIDATE_INT) && $this->playerPosition['x'] > 0) || !(filter_var($this->playerPosition['y'], FILTER_VALIDATE_INT) && $this->playerPosition['y'] > 0))
						throw new \Exception(\Language::getMessage("scenes", "scenemap_position_no_valid"));
				}

				if (empty($this->urlSubmitCode)) 
					throw new \Exception(\Language::getMessage("scenes", "scenemap_no_submit_code"));
				
			case "PointAndClick":
				
				break;
		}
	}

	public function runScript($code) {
		
		$data = array(
			'map'		=> $this->map,
			'player'	=> $this->player,
			'position'	=> $this->playerPosition
		);
		$this->session->set('ji_move_player', $data);
		if ($this->code->runScript($code)) {

			$data = $this->session->get('ji_move_player');
			$this->map->setMap($data['map']->getMap(true));

			$return = json_encode(array(
				'success'	=> true,
				'map'		=> array(
					'clickable' => $this->map->isClickable(),
					'tiles'		=> $this->map->getMap(true)
				)
			));

			$this->setPlayerPosition($data['position']);
		} else {
			$return = json_encode(array(
				'success'	=> false,
				'error'		=> $this->code->getError()
			));
		}

		$this->session->delete('ji_move_player');

		return $return;
	}

	public function setPlayer($player, $position) {
		$this->player = $player;
		$this->setPlayerPosition($position);
	}

	public function setPlayerPosition($position) {
		$this->playerPosition = $position;	
	}

	public function getPlayerPosition() {
		return $this->playerPosition;
	}

	public function isCodeNavegation() {
		$this->navegation = 'Code';
	}

	public function isPACNavegation() {
		$this->navegation = 'PointAndClick';
	}

	public function getMap() {
		return $this->map;
	}

	public function setUrlCode($url) {
		if (substr($url, 0, 4) != 'http')
			$url = 'http://' . $url;
		$this->urlSubmitCode = $url;
	}

	public function getUrlCode() {
		return $this->urlSubmitCode;
	}
}