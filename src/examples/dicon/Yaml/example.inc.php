<?php
define('__EDIR__', dirname(dirname(dirname(__FILE__))));
define('HOME_DIR',dirname(dirname(__EDIR__)));
define('EXAMPLE_DIR', HOME_DIR . '/src/examples');

require_once(HOME_DIR . '/s2container.inc.php'); 
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}

require_once('Spyc.php5');
require_once('Hello.class.php');
require_once('HelloConstructorInjection.class.php');
require_once('HelloMethodInjection.class.php');
require_once('HelloSetterInjection.class.php');
?>
