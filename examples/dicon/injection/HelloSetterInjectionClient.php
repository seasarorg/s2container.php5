<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
require_once('Hello.class.php');
require_once('HelloSetterInjection.class.php');

$PATH = "HelloSetterInjection.dicon";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>