<?php
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/HelloMethodInjection.dicon";
$PATH = EXAMPLE_DIR . "/dicon/HelloMethodInjection.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
?>
