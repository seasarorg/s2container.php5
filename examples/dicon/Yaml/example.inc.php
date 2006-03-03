<?php
error_reporting(E_ALL);
define('__WORKDIR__', dirname(__FILE__));
define('HOME_DIR', dirname(dirname(dirname(__WORKDIR__))));
define('EXAMPLE_DIR', HOME_DIR . '/examples');

require_once(HOME_DIR . '/s2container.inc.php');

//S2ContainerClassLoader::import(S2CONTAINER_PHP5);
function __autoload($class=null){
    S2ContainerClassLoader::load($class);
}

// please self install 'Spyc'
require_once('Spyc.php');
require_once('Hello.class.php');
require_once('HelloConstructorInjection.class.php');
require_once('HelloMethodInjection.class.php');
require_once('HelloSetterInjection.class.php');
?>
