<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/example.inc.php');

\seasar\util\ClassLoader::import(dirname(__FILE__) . '/classes');
$container = seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/example.dicon');
$service = $container->getComponent('Service');

print $service->add(2, 3) . PHP_EOL;
try {
    $result = $service->sub(3, 2);
} catch(Exception $e) {
    print get_class($e) . ' : ' . $e->getMessage() . PHP_EOL;
}
