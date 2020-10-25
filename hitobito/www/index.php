<?php

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to our webapp/config.php script.              |
// +---------------------------------------------------------------------------+

require_once("../webapp/config.php");

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the mojavi/mojavi.php script.              |
// +---------------------------------------------------------------------------+
require_once("../mojavi/mojavi.php");

if(DIRECTORY_SEPARATOR == '/'){
	ini_set('include_path', ini_get('include_path').':'.MO_WEBAPP_DIR.'/pear');
}else{
	// win
	ini_set('include_path', ini_get('include_path').';'.MO_WEBAPP_DIR.'\pear');
}
// +---------------------------------------------------------------------------+
// | Create our controller. For this file we're going to use a front           |
// | controller pattern. This pattern allows us to specify module and action   |
// | GET/POST parameters and it automatically detects them and finds the       |
// | expected action.                                                          |
// +---------------------------------------------------------------------------+
//$controller = Controller::newInstance('FrontWebController');
if(isset($_ENV['LOCAL_DEBUG']) && $_ENV['LOCAL_DEBUG'] == 'ON'){
//    $controller = Controller::newInstance('HNbLocalDebugWebController');
    $controller = Controller::newInstance('HNbWebController');
}else{
    $controller = Controller::newInstance('HNbWebController');
}

// +---------------------------------------------------------------------------+
// | Dispatch our request.                                                     |
// +---------------------------------------------------------------------------+
$controller->dispatch();

?>