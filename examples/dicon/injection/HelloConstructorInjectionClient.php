<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
require_once('Hello.class.php');
require_once('HelloConstructorInjection.class.php');

$PATH =	"HelloConstructorInjection.dicon";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();
		
$hello2 = $container->getComponent("hello");
$hello2->showMessage();

?>
