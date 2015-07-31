<?php

$config = array(

	'urlBase' 			=> '',
	'language' 			=> 'pt-br',
	'defaultController' => 'index',
	
	//Level 1 = Erros
	//Level 2 = Debug
	//Level 3 = Informações do usuario
	'log'				 => array(1, 2, 3),
	'logDir'			 => '',			//Se vázio, ficará app/logs

	'dirCache'			=> ''			//Se vázio, ficará app/cache
);