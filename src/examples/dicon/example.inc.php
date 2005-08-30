<?php
error_reporting(E_ALL);
define('HOME_DIR',dirname(dirname(dirname(dirname(__FILE__)))));
define('EXAMPLE_DIR',HOME_DIR . '/src/examples');

require_once(HOME_DIR . '/s2container.php5.ini'); 

require_once('Hello.class.php');
require_once('HelloConstructorInjection.class.php');
require_once('HelloMethodInjection.class.php');
require_once('HelloSetterInjection.class.php');

require_once('autobinding/Map.class.php');
require_once('autobinding/HashMap.class.php');
require_once('autobinding/AutoHelloConstructorInjection.class.php');
require_once('autobinding/AutoHelloConstructorInjection2.class.php');
require_once('autobinding/AutoHelloSetterInjection.class.php');

require_once('expression/ArgExp.class.php');        
require_once('expression/ComponentExp.class.php');        
require_once('expression/MethodExp.class.php'); 
?>