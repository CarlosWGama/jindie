<?php
/**
* 	JIndie
*	@package JIndie
*	@category core
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

final class Log {
	
	/**
	* Level 1 = Erros
	* Level 2 = Debug
	* Level 3 = Informações do usuario
	* @access private
	* @uses self::getFile('debug', 3)
	* @param string $fileName
	* @param int level
	*/
	private static function getFile($fileName, $level) {
		$logDir = Config::getInfo('general', 'logDir');
		if (empty($logDir))
			$logDir = APP_PATH.'logs/';

		if (empty($fileName)) {
			switch ($level) {
				case 1:
					$fileName = 'error_' .date('Y-m-d').'.txt';
					break;
				case 2:
					$fileName = 'debug_' .date('Y-m-d').'.txt';
					break;
				case 3:
				default:
					$fileName = 'log_' .date('Y-m-d').'.txt';
					break;
			}
		}

		return $logDir . $fileName;
	}

	/**
	* Escreve as mensagens em um arquivo de log
	* @uses Log::message('Teste')
	* @param string $message
	* @param int|array $levels
	* @param string $fileName
	*/
	public static function message($message, $levels = 3, $fileName = '') {
		if (!is_array($levels))
			$levels = array($levels);


		foreach ($levels as $level) {
			if (in_array($level, Config::getInfo('general', 'log'))) {
				$file = self::getFile($fileName, $level);

				switch ($level) {
					case 1:
						$message = '[ERROR] ' . $message . "(" . date('Y-m-d H:i:s') . ")\n";
						break;
					case 2:
						$message = '[DEBUG] ' . $message . "\n";
						break;
					case 3:
					default:
						$message = '[LOG] ' . $message . "\n";
						break;
				}

				file_put_contents($file, $message, FILE_APPEND);
			}	
		}
		
	}


}