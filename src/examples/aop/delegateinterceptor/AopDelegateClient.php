<?php
require_once(dirname(__FILE__) . '/delegate.inc.php');
$PATH = EXAMPLE_DIR . "/aop/delegateinterceptor/Delegate.dicon";
$PATH = EXAMPLE_DIR . "/aop/delegateinterceptor/Delegate.ini";
$container = S2ContainerFactory::create($PATH);
$base = $container->getComponent('Dummy');
$base->run();
?>