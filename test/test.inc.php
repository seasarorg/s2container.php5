<?php
//error_reporting(E_ALL);
error_reporting(E_ALL | E_STRICT);

/**
 * require default
 */
require_once dirname(dirname(__FILE__)) . '/S2Container.php'; 

/**
 * require pear package
 */
//require_once('S2Container/S2Container.php'); 

require_once('S2ContainerSplAutoLoad.php');

define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);
//define('S2CONTAINER_PHP5_DEBUG_EVAL',true);
//define('S2CONTAINER_PHP5_SIMPLE_LOG_FILE',dirname(__FILE__) . '/simple.log');

//define('S2CONTAINER_PHP5_CACHE_LITE_INI', dirname(__FILE__) . '/cache_lite.ini');

//define('S2CONTAINER_PHP5_AUTO_DI_INTERFACE', true);

?>
