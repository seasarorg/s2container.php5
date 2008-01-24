<?php
require_once(dirname(__FILE__) . '/../S2Container.php');

seasar::util::ClassLoader::import(dirname(__FILE__) . '/classes', array(), true);
seasar::Config::$LOG_LEVEL = seasar::log::SimpleLogger::WARN;
seasar::container::Config::$DOM_VALIDATE = true;
$container = seasar::container::factory::S2ContainerFactory::create(dirname(__FILE__) . '/dicon/sample.dicon');

$bar = $container->getComponent('Bar');
var_dump($bar);

$foo = $container->getComponent('Foo');
var_dump($foo);

$hoge = $container->getComponent('Hoge');
var_dump($hoge);
