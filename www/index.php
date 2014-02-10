<?php

define('CATFWCORE', dirname(__FILE__).'/../catfwcore/'); // Assuming Linux environment
require_once CATFWCORE.'requests.class.php';

if (CataloniaFramework\Requests::getServerName() == 'json.aeriatest.loc') {
    $s_mode_response = 'json';
} elseif (CataloniaFramework\Requests::getServerName() == 'html.aeriatest.loc') {
    // Used for Debug
    $s_mode_response = 'html';
} else {
    $s_mode_response = 'xml';
}
define('RESPONSE_MODE', $s_mode_response);

include '../core/basics.php';

if(file_exists(PATH_SERVICES.Router::$service.'.php'))
{
	CORE::$SERVICE = new Service(Router::$service);
}
else
{
	CORE::$SERVICE = new SERVICE('404');
}

CORE::$SERVICE->renderPage();
