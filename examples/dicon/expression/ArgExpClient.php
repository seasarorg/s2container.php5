<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/expression/ArgExp.dicon";
		
$container = S2ContainerFactory::create($PATH);
$sample = $container->getComponent('sample1');
$sample->showValue();
$sample->showMessage();
$sample = $container->getComponent('sample2');
$sample->showValue();
$sample->showMessage();

?>