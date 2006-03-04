<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/autoregister/autoFile.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$hoge = $container->getComponent('hoge');
print_r($hoge);

$service = $container->getComponent('service');
print_r($service);

if(!$container->hasComponentDef('HogeDao')){
     print "HogeDao not found.\n";   
}
?>