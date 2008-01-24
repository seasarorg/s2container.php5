<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/example.inc.php');

seasar::util::ClassLoader::import(dirname(__FILE__) . '/classes');
$container = seasar::container::factory::S2ContainerFactory::create(dirname(__FILE__) . '/example.dicon');
$service = $container->getComponent('Service');
print get_class($service) . PHP_EOL;
$result = $service->add(2, 3);
