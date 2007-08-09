<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

S2ContainerApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
$container = S2ContainerApplicationContext::create();
$hello = $container->getComponent('Hello');
$hello->sayHello();
$printer = $container->getComponent('Printer');
$printer->printOut('Hello World !');
