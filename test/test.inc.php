<?php
error_reporting(E_ALL);
//error_reporting(E_ALL | E_STRICT);

/**
 * require default
 */
require_once(dirname(dirname(__FILE__)) . '/s2container.inc.php'); 

/**
 * require pear package
 */
//require_once('S2Container/S2Container.php'); 

function __autoload($class = null)
{
    if($class != null){
        include_once("$class.class.php");
    }
}

//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG_EVAL);
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);

?>
