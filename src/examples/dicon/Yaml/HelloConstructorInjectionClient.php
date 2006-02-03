<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH =	EXAMPLE_DIR . "/dicon/Yaml/HelloConstructorInjection.dicon.yml";

$container = S2ContainerFactory::create($PATH);

$hello = $container->getComponent('Hello');
$hello->showMessage();
		
$hello2 = $container->getComponent("hello");
echo $hello2->showMessage();

?>
