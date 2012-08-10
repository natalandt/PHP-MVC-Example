<?php

//Note line below: Prefer to use '/' instead of . DS . because it is prettier
//define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

//Get the current URL
$url = $_GET['url'];

require_once (ROOT . '/config/config.php');

// Display errors in production mode
if (DEV_ENV == true) 
{
	error_reporting(E_ALL);
	ini_set('display_errors',1);
} 
else 
{
	error_reporting(E_ALL);
	ini_set('display_errors',0);
	ini_set('log_errors', 1);
	//Write all errors to log
	ini_set('error_log', ROOT . '/tmp/logs/error.log');
}

// Load the MVC library
require_once (ROOT . '/library/load.php');
