<?php
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH = dirname(__FILE__) . "/HelloSetterInjection.dicon.yml";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>
