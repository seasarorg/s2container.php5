<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/autobinding/AutoHelloSetterInjection.dicon";
$PATH = EXAMPLE_DIR . "/dicon/autobinding/AutoHelloSetterInjection.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>