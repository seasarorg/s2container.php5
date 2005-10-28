<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/expression/ComponentExp.dicon";
$PATH = EXAMPLE_DIR . "/dicon/expression/ComponentExp.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('hello');
$hello->showMessageA();
$hello->showMessageB();
$hello = $container->getComponent('hello2');
$hello->showMessageA();
$hello->showMessageB();
$hello = $container->getComponent('hello3');
$hello->showMessageA();
$hello->showMessageB();

?>