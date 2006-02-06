<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH =	EXAMPLE_DIR . "/dicon/autobinding/AutoHelloConstructorInjection.dicon";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
		
?>
