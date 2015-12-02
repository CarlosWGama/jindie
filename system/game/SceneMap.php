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

	/**
	* Define o tipo de navegação (PointAndClick ou Code)
	* @access protected
	* @var string
	*/
	protected $navegation = 'PointAndClick';

	/**
	* Define a imagem do personagem no mapa
	* @access protected
	* @var string
	*/
	protected $player;

	/**
	* Define a posição do personagem no mapa
	* @access protected
	* @var array |vector(x,y)
	*/
	protected $playerPosition;

	/**
	* Objeto MapGenerator para gerar o mapa
	* @access protected
	* @var MapGenerator
	*/
	protected $map;

	/**
	* Objeto CodeReader para interpretar o código do usuário
	* @access protected
	* @var CodeReader
	*/
	protected $code;

	/**
	* URL para onde será enviado o código do usuário
	* @access protected
	* @var string
	*/
	protected $urlSubmitCode = '';

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
	}

	/****** CONFIGURATION *****/
	/**
	* Inicia o player em uma posição
	* @param string $player | Imagem que representa o personagem
	* @param array $position | vetor(x, y)
	*/
	public function setPlayer($player, $position) {
		$this->player = $player;
		$this->setPlayerPosition($position);
	}

	/**
	* Ativa o cenário com  código para navegação
	*/
	public function isCodeNavegation() {
		$this->navegation = 'Code';
	}

	/**
	* Ativa o cenário para navegação via Point-And-Click
	*/
	public function isPACNavegation() {
		$this->navegation = 'PointAndClick';
	}

	/**
	* Define a url para onde será enviado o código do usuário
	* @param string $url
	*/
	public function setUrlCode($url) {
		if (substr($url, 0, 4) != 'http')
			$url = 'http://' . $url;
		$this->urlSubmitCode = $url;
	}

	/**
	* Define a posição do heroi no cenário | vetor(x, y)
	* @param array $position
	*/
	public function setPlayerPosition($position) {
		$this->playerPosition = $position;	
	}

	/******* START MAP *******/
	/**
	* Gera a Scene
	*/
	public function showScene() {
		\Log::message(\Language::getMessage('log', 'debug_scene_show_scene', array('scene' => "SceneMap")), 2);
		
		if ($this->navegation == "PointAndClick") {
			$this->map->setClickable(true);		//Pode Clicar no mapa
			$hasCode = false;					//Não tem campo para digitar código
		} else {
			
			if ($this->map->hasTile($this->playerPosition)) 	//Adicionar o personagem em um tile já existente
				$this->map->alterTile($this->playerPosition, 'object', $this->player);	
			else {												//Adiciona o personagem em um posição não existe usando o tile padrão
				$tile = $this->map->getDefaultTile();
				$tile['object'] = $this->player;
				$this->map->addTileAtPosition($this->playerPosition, $tile);	
			}
			
			$this->map->setClickable(false);		//Não pode clicar no mapa
			$hasCode = true;						//Tem campo para digitar o código
			$urlSubmitCode = $this->getUrlCode();	
		}

		$map = $this->map->generate(true);
		
		require(APP_PATH."views/game/scenes/map.php");	
	}

	/**
	* Verifica se todos os requisitos foram realizados para executar a Scene
	*/
	public function check () {
		\Log::message(\Language::getMessage('log', 'debug_scene_start_check', array('scene' => "SceneMap")), 2);

		if (empty($this->map->getMap(true)))
			throw new \Exception(\Language::getMessage("scenes", "scenemap_no_map"));

		switch ($this->navegation) {
			case "Code":

				//Imagem do jogador não definida
				if (empty($this->player))
					throw new \Exception(\Language::getMessage("scenes", "scenemap_no_player", 36));

				//Posição do jogador não definido
				if (!is_array($this->playerPosition) || count($this->playerPosition) != 2) 
					throw new \Exception(\Language::getMessage("scenes", "scenemap_position_no_vector", 37));
				else {

					$this->playerPosition = array('x' => current($this->playerPosition), 'y' => end($this->playerPosition));

					//Posição do jogador não válida
					if (!(filter_var($this->playerPosition['x'], FILTER_VALIDATE_INT) && $this->playerPosition['x'] > 0) || !(filter_var($this->playerPosition['y'], FILTER_VALIDATE_INT) && $this->playerPosition['y'] > 0))
						throw new \Exception(\Language::getMessage("scenes", "scenemap_position_no_valid", 38));
				}

				//Ñão definido url que receberá os códigos do usuário
				if (empty($this->urlSubmitCode)) 
					throw new \Exception(\Language::getMessage("scenes", "scenemap_no_submit_code", 39));
				
			case "PointAndClick":
				
				break;
		}

		\Log::message(\Language::getMessage('log', 'debug_scene_end_check', array('scene' => "SceneMap")), 2);
	}

	/******* Exec Code *******/
	/**
	* Executa ro código do usuário para mover o personagem e retornar um json de sucesso ou fracasso
	* @param string $code
	* @return string (json)
	*/
	public function runScript($code) {
		\Log::message(\Language::getMessage('log', 'debug_scenemap_run_code'), 2);
		//Formata o código
		$code = html_entity_decode($code, ENT_QUOTES);
		$code = str_replace("\"", "'", $code);

		//Passa os dados para a classe Navegation
		$data = array(
			'map'		=> unserialize(serialize($this->map)), 	//Para a classe não ser passada como referência e sim valor
			'player'	=> $this->player,
			'position'	=> $this->playerPosition
		);
		$this->code->getCode()->setData($data);

		try {

			//Tenta executar código do usuário
			if ($this->code->runScript($code)) {

				//Recupera e atualiza
				$data = $this->code->getCode()->getData();
				$this->map->setMap($data['map']->getMap(true));

				//Mensagem de Retorno
				$return = array(
					'success'	=> true,
					'map'		=> array(
						'clickable' => $this->map->isClickable(),
						'tiles'		=> $this->map->getMap(true)
					)
				);

				//Se o usuário encostou em outro objeto com ação
				if (isset($data['action']))
					$return['action'] = $data['action'];

				$return = json_encode($return);

				//Atualiza a posição do personagem
				$this->setPlayerPosition($data['position']);
			} else {
				
				//Falha em executar o código
				//Mensagem de Retorno
				$return = json_encode(array(
					'success'	=> false,
					'error'		=> $this->code->getError()
				));
			}			
		} catch (\Exception $ex) {
			//Error no código do desenvolvedor
			//Mensagem de Retorno
			$return = json_encode(array(
				'success'	=> false,
				'error'		=> $ex->getMessage()
			));
		}
		
		//Remove da sessão os dados enviados para navegation
		$this->code->getCode()->clearData();

		return $return;
	}

	/******* GETS *******/
	/**
	* @return array | vetor(x,y)
	*/
	public function getPlayerPosition() {
		return $this->playerPosition;
	}

	/**
	* @return MapGenerator
	*/
	public function getMap() {
		return $this->map;
	}

	/**
	* @return string
	*/
	public function getUrlCode() {
		return $this->urlSubmitCode;
	}
}