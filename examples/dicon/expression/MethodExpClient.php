<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
require_once('MethodExp.class.php'); 

$PATH = EXAMPLE_DIR . "/dicon/expression/MethodExp.dicon";
		
$container = S2ContainerFactory::create($PATH);
$method = $container->getComponent('method');

?>