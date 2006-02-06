<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
require_once('Hello.class.php');
require_once('Map.class.php');
require_once('HashMap.class.php');
require_once('AutoHelloConstructorInjection.class.php');

$PATH =	"AutoHelloConstructorInjection.dicon";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
		
?>
