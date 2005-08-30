<?php
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/HelloSetterInjection.dicon";
$PATH = EXAMPLE_DIR . "/dicon/HelloSetterInjection.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>