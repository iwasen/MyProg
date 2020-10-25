<?php

// +---------------------------------------------------------------------------+
// | Should we run the system in debug mode? When this is on, there may be     |
// | various side-effects. But for the time being it only deletes the cache    |
// | upon start-up.                                                            |
// |                                                                           |
// | This should stay on while you're developing your application, because     |
// | many errors can stem from the fact that you're using an old cache file.   |
// +---------------------------------------------------------------------------+
if(isset($_ENV['LOCAL_DEBUG']) && $_ENV['LOCAL_DEBUG'] == 'ON'){
	define('MO_DEBUG', TRUE);
}else{
	define('MO_DEBUG', FALSE);
}

// adodb debug
define('ADODB_DEBUG', FALSE);


// +---------------------------------------------------------------------------+
// | The PHP error reporting level.                                            |
// |                                                                           |
// | See: http://www.php.net/error_reporting                                   |
// +---------------------------------------------------------------------------+
//define('MO_ERROR_REPORTING', E_ALL | E_STRICT);
if(isset($_ENV['LOCAL_DEBUG']) && $_ENV['LOCAL_DEBUG'] == 'ON'){
	define('MO_ERROR_REPORTING', E_ALL);
}else{
	define('MO_ERROR_REPORTING', 0);
}
// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the mojavi package. This directory         |
// | contains all the Mojavi packages.                                         |
// +---------------------------------------------------------------------------+
define('MO_APP_DIR', dirname(dirname(__FILE__)).'/mojavi');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to your web application directory. This       |
// | directory is the root of your web application, which includes the core    |
// | configuration files and related web application data.                     |
// +---------------------------------------------------------------------------+
define('MO_WEBAPP_DIR', dirname(dirname(__FILE__)).'/webapp');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the directory where cache files will be    |
// | stored.                                                                   |
// |                                                                           |
// | NOTE: If you're going to use a public temp directory, make sure this is a |
// |       sub-directory of the temp directory. The cache system will attempt  |
// |       to clean up *ALL* data in this directory.                           |
// +---------------------------------------------------------------------------+
define('MO_CACHE_DIR', MO_WEBAPP_DIR . '/cache');

// Smarty

define('MO_SMARTY_DIR', MO_WEBAPP_DIR.'/lib/smarty/libs');
define('MO_SMARTY_CACHE_DIR', MO_WEBAPP_DIR.'/smarty/cache');


// Database

define('HNB_DB_HOST', isset($_ENV['HITOBITO_DB_HOST']) ? $_ENV['HITOBITO_DB_HOST'] : 'localhost');
define('HNB_DB_USER', 'postgres');
define('HNB_DB_PASS', 'password');
define('HNB_DB_NAME', 'hitobito');

//site

define('HNB_HTTP_URL', 'http://hitobito.net');

?>
