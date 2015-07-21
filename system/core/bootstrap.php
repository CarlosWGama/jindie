<?php
//Define
define('CONTROLLERS_PATH', 		__DIR__.'/../../app/controllers/');
define('FILTERS_PATH', 			__DIR__.'/../../app/filters/');
define('MODELS_PATH', 			__DIR__.'/../../app/models/');
define('VIEWS_PATH', 			__DIR__.'/../../app/views/');
define('LIBRARIES_JI_PATH', 	__DIR__.'/../libraries/');
define('LIBRARIES_PATH', 		__DIR__.'/../../app/libraries/');
define('LANGUAGE_PATH', 		__DIR__.'/../language/');
define('GAME_PATH', 			__DIR__.'/../../app/game/');
define('GAME_JI_PATH', 			__DIR__.'/../game/');

define('ROOT_PATH', dirname(__FILE__).'/../../');
define('SYSTEM_PATH', dirname(__FILE__).'/../');
define('APP_PATH', dirname(__FILE__).'/../../app/');


//Require
require_once(dirname(__FILE__).'/../game/Game.php');
require_once(dirname(__FILE__).'/Log.php');
require_once(dirname(__FILE__).'/Input.php');
require_once(dirname(__FILE__).'/DefaultStructure.php');
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/Filter.php');
require_once(dirname(__FILE__).'/Loader.php');
require_once(dirname(__FILE__).'/Model.php');
require_once(dirname(__FILE__).'/Language.php');
require_once(dirname(__FILE__).'/Errors.php');
require_once(dirname(__FILE__).'/ControllerFactory.php');
require_once(dirname(__FILE__).'/FilterFactory.php');
require_once(dirname(__FILE__).'/../utils/WordUtil.php');
require_once(dirname(__FILE__).'/Config.php');
require_once(dirname(__FILE__).'/../libraries/login/ISocialLogin.php');

