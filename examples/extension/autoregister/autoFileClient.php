<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/extension/autoregister/autoFile.dicon";

$container = S2ContainerFactory::create($PATH);
$container->init();
$hoge = $container->getComponent('hoge');
print_r($hoge);

$service = $container->getComponent('service');
print_r($service);

$entity = $container->getComponent('HogeEntity');
print_r($entity);

$action = $container->getComponent('HogeAction');
print_r($action);

if(!$container->hasComponentDef('HogeDao')){
     print "HogeDao not found.\n";   
}
?>