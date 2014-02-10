<?php

ini_set('error_reporting', E_ALL);
header('P3P: CP="CAO PSA OUR"');
if (RESPONSE_MODE == 'xml') {
    header('Content-type: application/xml; charset=utf-8');
} elseif (RESPONSE_MODE == 'json') {
    header('Content-type: application/json; charset=utf-8');
} else {
    // For my Debug and future developments
    header('Content-type: text/html; charset=utf-8');
}


define('PATH_SERVER','http://'.$_SERVER['SERVER_NAME']);
define('PATH_BASE','../');
define('PATH_CORE',PATH_BASE.'core/');
define('PATH_SERVICES',PATH_BASE.'services/');

define('PATH_LIBRARIES', dirname(__FILE__).'/../libraries/');

include 'router.class.php';
include 'service.class.php';

class CORE{
	public static $SERVICE = 0;
	
	public static function redirect($url)
	{
		header('Location: '.$url);
		exit();
	}
}


class IO{

	public static function _var($key,$default='')
	{
		$stack = end(Controller::$variableStack);
		return isset($stack[$key]) ? $stack[$key] : $default;
	}

	public static function post($key,$default='')
	{
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	}
	
	public static function get($key,$default='')
	{
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	}
	
	public static function error($text)
	{
		return '<error>'.$text.'</error>';
	}
}



function __autoload($className)
{
	if(class_exists($className))
		return true;

	if(file_exists(PATH_LIBRARIES.$className.'.class.php'))
		include(PATH_LIBRARIES.$className.'.class.php');
}
