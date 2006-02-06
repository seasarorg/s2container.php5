<?php
require_once(dirname(__FILE__) . '/prototype.inc.php');

$PATH = EXAMPLE_DIR . "/aop/prototypedelegateinterceptor/PrototypeDelegate.dicon";
$container = S2ContainerFactory::create($PATH);
$base = $container->getComponent('Dummy');
for ($i = 0; $i < 5; ++$i) {
   $base->run();
}
?>