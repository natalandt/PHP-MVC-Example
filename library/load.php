<?php

require_once (ROOT . '/library/common.php');

/** Main Call Function **/

function load() {
	global $url;
	global $default;

	$queryString = array();

	if (!isset($url)) 
	{
		// Load defaults
		$controller = $default['controller'];
		$action = $default['action'];
	} 
	else 
	{
		// Transform url into array and load controller and action
		$url = routeURL($url);
		$urlArray = array();
		$urlArray = explode("/",$url);
		$controller = $urlArray[0];
		array_shift($urlArray);
		if (isset($urlArray[0])) {
			$action = $urlArray[0];
			array_shift($urlArray);
		} else {
			$action = 'index'; // Default Action
		}
		$queryString = $urlArray;
	}
	
	$controllerName = ucfirst($controller).'Controller';

	$dispatch = new $controllerName($controller,$action);
	
	if ((int)method_exists($controllerName, $action)) 
	{
		call_user_func_array(array($dispatch,"before"),$queryString);
		call_user_func_array(array($dispatch,$action),$queryString);
		call_user_func_array(array($dispatch,"after"),$queryString);
	} 
	else 
	{
		/* Error Generation Code Here */
	}
}

gzipOutput() || ob_start("ob_gzhandler");

$inflect =& new Inflection();

setReporting();
removeMagicQuotes();
unregisterGlobals();

load();