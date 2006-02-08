<?php
require_once('example.inc.php');
require_once('classes/Greeting.class.php');
require_once('classes/GreetingClient.class.php');

$PATH = EXAMPLE_DIR . "/autoregister/GreetingMain4.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$g = $container->getComponent("greetingClient");
$g->execute();

?>