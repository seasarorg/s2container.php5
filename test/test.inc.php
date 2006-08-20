<?php
error_reporting(E_ALL);
//error_reporting(E_ALL | E_STRICT);

/**
 * require default
 */
require_once(dirname(dirname(__FILE__)) . '/S2Container.php'); 

/**
 * require pear package
 */
//require_once('S2Container/S2Container.php'); 

S2ContainerClassLoader::import(S2CONTAINER_PHP5);
function __autoload($class = null)
{
    S2ContainerClassLoader::load($class);
/*
    if($class != null){
        include_once("$class.class.php");
    }
*/
}

define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);
//define('S2CONTAINER_PHP5_DEBUG_EVAL',true);
//define('S2CONTAINER_PHP5_SIMPLE_LOG_FILE',dirname(__FILE__) . '/simple.log');
?>
