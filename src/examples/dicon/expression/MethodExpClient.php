<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/expression/MethodExp.dicon";
		
$container = S2ContainerFactory::create($PATH);
$method = $container->getComponent('method');

?>