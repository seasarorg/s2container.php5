<?php
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH =	EXAMPLE_DIR . "/dicon/HelloConstructorInjection.dicon";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
		
$hello2 = $container->getComponent("hello");
$hello2->showMessage();

?>
