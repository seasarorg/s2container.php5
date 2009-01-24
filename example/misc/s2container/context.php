<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

\seasar\container\S2ApplicationContext::import(dirname(__FILE__) . '/classes');
$container = \seasar\container\S2ApplicationContext::create();
$service    = $container->getComponent('Service');

print get_class($service->container) . PHP_EOL;
