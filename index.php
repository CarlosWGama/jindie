<?php
########################################
#########        JIndie        #########
# Projeto:
# Autor:
# Data de desenvolvimento:
########################################

date_default_timezone_set('America/Sao_Paulo');


print_r($_GET);
//Erros
$errors = "hard"; //hard | medium | easy

//Charset
$charset = "utf8"; //utf8 | iso

//
require (dirname(__FILE__).'/system/core/JIndie.php');
$JIndie = new JIndie($errors, $charset);
$JIndie->run();