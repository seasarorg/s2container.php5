<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

S2ContainerApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
S2ContainerApplicationContext::setExcludePattern('/^Printer$/');
$container = S2ContainerApplicationContext::create();
$printer = $container->getComponent('console');
$printer->printOut('Hello Wolrd !');
