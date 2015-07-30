<?php
//Define
define('CONTROLLERS_PATH', 		__DIR__.'/../../app/controllers/');
define('FILTERS_PATH', 			__DIR__.'/../../app/filters/');
define('MODELS_JI_PATH', 		__DIR__.'/../models/');
define('MODELS_PATH', 			__DIR__.'/../../app/models/');
define('VIEWS_PATH', 			__DIR__.'/../../app/views/');
define('LIBRARIES_JI_PATH', 	__DIR__.'/../libraries/');
define('LIBRARIES_PATH', 		__DIR__.'/../../app/libraries/');
define('LANGUAGE_PATH', 		__DIR__.'/../language/');
define('GAME_PATH', 			__DIR__.'/../../app/game/');
define('GAME_JI_PATH', 			__DIR__.'/../game/');
define('UTIL_JI_PATH', 			__DIR__.'/../utils/');
define('CORE_PATH', 			__DIR__.'/');

define('ROOT_PATH', 			__DIR__.'/../../');
define('SYSTEM_PATH', 			__DIR__.'/../');
define('APP_PATH', 				__DIR__.'/../../app/');


//Require
require_once(GAME_JI_PATH.'Game.php');
require_once(CORE_PATH.'Config.php');
require_once(CORE_PATH.'Log.php');
require_once(CORE_PATH.'Input.php');
require_once(CORE_PATH.'DefaultStructure.php');
require_once(CORE_PATH.'Controller.php');
require_once(CORE_PATH.'Filter.php');
require_once(CORE_PATH.'Loader.php');
require_once(CORE_PATH.'Model.php');
require_once(CORE_PATH.'Language.php');
require_once(CORE_PATH.'Errors.php');
require_once(CORE_PATH.'ControllerFactory.php');
require_once(CORE_PATH.'FilterFactory.php');

//Util
require_once(UTIL_JI_PATH.'WordsUtil.php');
require_once(UTIL_JI_PATH.'JSONUtil.php');

//Interfaces
require_once(LIBRARIES_JI_PATH.'login/ISocialLogin.php');
require_once(LIBRARIES_JI_PATH.'code/ICode.php');
require_once(MODELS_JI_PATH.'IMessage.php');
require_once(MODELS_JI_PATH.'IUser.php');
//
Config::autoLoad();
