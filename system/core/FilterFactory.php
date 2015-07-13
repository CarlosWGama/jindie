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

final class FilterFactory {
	
	public function runFilters() {

		foreach (glob(FILTERS_PATH."*.php") as $filter) {
			require_once($filter);
			$filterName = explode('/', $filter);
			$filterName = end($filterName);
			$filterName = substr($filterName ,0, -4);
			try {
				Log::message(Language::getMessage('log', 'debug_filter_start', array('filter' => $filterName)), 2);

				$f = new $filterName;
    			if (is_subclass_of($f, 'Filter')) {
    				
    				if ($f->check()) {
    					Log::message(Language::getMessage('log', 'debug_filter', array('filter' => $filterName)), 2);
    					$f->run();
    				}
    			} else {
					Log::message(Language::getMessage('log', 'debug_filter_not_filter', array('filter' => $filterName)), 2);    	
    			}
			} catch (Exception $ex) {
				Log::message(Language::getMessage('log', 'debug_filter_fail', array('filter' => $filterName)), 2);
				continue; //Não é uma classe
			}
		}		
	}

}