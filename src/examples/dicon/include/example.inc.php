<?php
error_reporting(E_ALL);
define('HOME_DIR',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('EXAMPLE_DIR',HOME_DIR . '/src/examples');

require_once(HOME_DIR . '/s2container.inc.php'); 

require_once('Hello.class.php');         
require_once('HelloClient.class.php');         
require_once('HelloImpl.class.php');         
require_once('RootHelloClient.class.php'); 
require_once('AaaHelloClient.class.php');
require_once('BbbHelloClient.class.php');
       
?>
