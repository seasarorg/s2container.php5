<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
require_once('greeting/Greeting.class.php');
require_once('greeting/GreetingClient.class.php');

$PATH = EXAMPLE_DIR . "/autoregister/greeting.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$g = $container->getComponent("greetingClient");
$g->execute();

?>