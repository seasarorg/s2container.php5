<?php
error_reporting(E_ALL);
define('HOME_DIR',dirname(dirname(__FILE__)));
define('EXAMPLE_DIR',HOME_DIR . '/examples');

require_once(HOME_DIR . '/S2Container.php'); 

if(class_exists("S2ContainerClassLoader")){
    S2ContainerClassLoader::import(S2CONTAINER_PHP5);
    function __autoload($class=null){
        if(S2ContainerClassLoader::load($class)){return;}
    }
}
?>
