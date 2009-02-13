<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

\seasar\util\ClassLoader::import(dirname(__FILE__) . '/classes');
$container = \seasar\container\factory\S2ContainerFactory::create(dirname(__FILE__) . '/example.dicon');
$action = $container->getComponent('Action');
var_dump($action);
