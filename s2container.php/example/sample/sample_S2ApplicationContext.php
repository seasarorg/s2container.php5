<?php
require_once(dirname(__FILE__) . '/../S2Container.php');

seasar::Config::$LOG_LEVEL = seasar::log::SimpleLogger::WARN;
seasar::container::S2ApplicationContext::import(dirname(__FILE__) . '/classes', array(), true);
$container = seasar::container::S2ApplicationContext::create();

$bar = $container->getComponent('Bar');
var_dump($bar);

$foo = $container->getComponent('Foo');
var_dump($foo);

$hoge = $container->getComponent('Hoge');
var_dump($hoge);
