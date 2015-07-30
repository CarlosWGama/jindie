<?php


class Map extends Controller {
	

	public function __construct() {
		parent::__construct();

		$this->loadLibrary('mapGenerator', 'map');
	}

	public function index() {

		//Size Image
		$this->map->setImageSize(32);

		//Size of Map
		$this->map->setMapSize(array(10, 10));
		$this->map->setMapSize(10, 2);

		//Default
		$default = array(
			'field' 	=> 'http://localhost/map/fields/ground/grass.jpg',
			'object'	=> '',
			'rule'		=> '',
		);
		$this->map->setDefaultTile($default);

		//Add Tile
		$tile = array('field' => 'http://localhost/map/fields/ground/grass.jpg', 'object' => "http://localhost/map/objects/persons/person10.png");
		for ($i = 1; $i <= 14; $i++) 
			$this->map->addTile($tile);

		//Add at Position
		$tile = array('field' => 'http://localhost/map/fields/water/water4.jpg', 'object' => $this->map->getColor('green'), 'url' => "http://local.jindie.com.br/map/redirect");
		$this->map->addTileAtPosition(array(8, 1), $tile);

		$tile = array('field' => 'http://localhost/map/fields/water/water4.jpg', 'object' => $this->map->getColor('blue'), 'url' => "http://local.jindie.com.br/map/alert");
		$this->map->addTileAtPosition(array(9, 1), $tile);

		$tile = array('field' => 'http://localhost/map/fields/water/water4.jpg', 'object' => $this->map->getColor('red'), 'url' => "http://local.jindie.com.br/map/confirm");
		$this->map->addTileAtPosition(array(10, 2), $tile);
		
		//Alter
		$this->map->alterTile(array(2, 1), 'object', 'http://localhost/map/objects/persons/person15.png');
		$this->map->alterTile(array(2, 1), 'url', 'http://localhost/map/objects/persons/person15.png');

		//Get Tile
		$tile = $this->map->getTile(array(2, 1));
		$tile = $this->map->getTile(2, 1);
		print_r($tile);die;

		$map = $this->map->generate();
		echo "AAA: <br/>";
		echo $map;
	}

	public function map() {

		$this->map->setDefaultTile(array('field' => 'http://localhost/map/fields/ground/floor2.jpg'));
		

		//$tiles[1][1] = array('field' => 'http://localhost/map/fields/water/water4.jpg', 'object' => 'http://localhost/map/objects/persons/person22.png', 'url' => "http://local.jindie.com.br/map/redirect");
		$tiles[4][10] = array('field' => 'http://localhost/map/fields/ground/floor2.jpg', 'object' => 'http://localhost/map/objects/furnitures/notebook1.png', 'url' => "http://local.jindie.com.br/map/redirect");

		$this->map->setMap($tiles);
		echo $this->map->generate();
			
	}

	public function redirect() {
		echo JSONUtil::redirect('http://google.com');
	}

	public function alert() {
		echo JSONUtil::alert('É não deu');
	}

	public function confirm() {
		echo JSONUtil::question('É isso?', 'http://local.jindie.com.br/map/confirm2');
	}

	public function confirm2() {
		$result = $this->input->post('result');
		echo JSONUtil::alert(($result == 'true' ? "Verdade" : 'mentira'));
	}

}