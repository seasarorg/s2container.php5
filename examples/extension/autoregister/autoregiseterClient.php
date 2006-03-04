<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/autoregister/test.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$a = $container->getComponent("a");
$b = $container->getComponent("bbB");
$c = $container->getComponent("C");
$c->calc(2,3);
$b->calc(2,3);

print  get_class($c);

if(!$container->hasComponentDef('D')){
   print " component [ D ] not found.\n"  ; 
}

interface IC {
    function calc($a,$b);
}
?>