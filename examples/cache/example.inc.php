<?php
error_reporting(E_ALL);
define('HOME_DIR', dirname(dirname(dirname(__FILE__))));
define('EXAMPLE_DIR', HOME_DIR . '/examples');

require_once(HOME_DIR . '/s2container.inc.php'); 
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}
?>
