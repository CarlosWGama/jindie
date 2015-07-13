<?php

class Admin extends Filter {
	
	protected $url = "/admin/(:any:)/(:num:)";

	public function run() {
		echo "Entrou no filtro";
		die;
	}

}