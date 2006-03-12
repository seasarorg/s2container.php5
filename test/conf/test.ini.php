<?php
define('HOME_DIR',dirname(dirname(dirname(__FILE__))));
define('SRC_DIR',HOME_DIR . '/src');
define('TEST_DIR',HOME_DIR . '/test');

/**
 * require default
 */
require_once(HOME_DIR . '/s2container.inc.php'); 

/**
 * require pear package
 */
//require_once('S2Container/S2Container.php'); 

/**
 * require phar package
require_once(HOME_DIR . '/build/phar/s2container.php5-1.1.0-beta2.phar'); 
define("S2CONTAINER_PHP5", "phar://s2container.php5-1.1.0-beta2.phar/s2container.php5");
require_once(S2CONTAINER_PHP5 . "/S2ContainerClassLoader.class.php");
require_once(S2CONTAINER_PHP5 . "/S2ContainerMessageUtil.class.php");
if( class_exists("S2ContainerMessageUtil") ){
    S2ContainerMessageUtil::addMessageResource(
                       S2CONTAINER_PHP5 . '/SSRMessages.properties');
}
 */

S2ContainerClassLoader::import(S2CONTAINER_PHP5);
function __autoload($class=null){
    S2ContainerClassLoader::load($class);
    //@include_once("$class.class.php");
}

define('S2CONTAINER_PHP5_APP_DICON',TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/app.dicon');
//define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);

require_once(TEST_DIR . '/conf/simpletest.inc.php');
require_once(TEST_DIR . '/conf/sample.inc.php');

function requireOnce($dir){
    $entries = scandir($dir);
    foreach($entries as $entry){
        if(preg_match("/\.php$/",$entry)){
            $path = "$dir/$entry";
            require_once($path);
        }
    }
}

/*
function requireOnce($dir){
    $d = dir($dir);
    while (false !== ($entry = $d->read())) {
        if(preg_match("/\.php$/",$entry)){
            $path = "$dir/$entry";
            require_once($path);
        }
    }
    $d->close();
}
*/

?>