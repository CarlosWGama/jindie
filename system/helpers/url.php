<?php




if (!function_exists('site_url')) {
	function site_url($url = "") {
		$urlBase = Config::getInfo('general', 'urlBase');
		return $urlBase . $url;
	}
}








