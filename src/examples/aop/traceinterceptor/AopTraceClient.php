<?php
require_once(dirname(__FILE__) . '/trace.inc.php');

$PATH = EXAMPLE_DIR . "/aop/traceinterceptor/Trace.dicon";
$PATH = EXAMPLE_DIR . "/aop/traceinterceptor/Trace.ini";

$container = S2ContainerFactory::create($PATH);
$date = $container->getComponent('Date');
$date->getTime();
?>