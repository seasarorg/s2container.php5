<?php
require_once(dirname(__FILE__) . '/original.inc.php');
$PATH = EXAMPLE_DIR . "/aop/originalinterceptor/Measurement.dicon";

$container = S2ContainerFactory::create($PATH);
$heavyProcess = $container->getComponent('HeavyProcess');
$heavyProcess->heavy();
?>