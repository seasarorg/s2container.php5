<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH =	EXAMPLE_DIR . "/dicon/autobinding/AutoHelloConstructorInjection2.dicon";
$PATH =	EXAMPLE_DIR . "/dicon/autobinding/AutoHelloConstructorInjection2.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
?>
