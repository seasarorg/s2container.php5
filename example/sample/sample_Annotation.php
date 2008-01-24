<?php
require_once(dirname(__FILE__) . '/../S2Container.php');

seasar::Config::$LOG_LEVEL = seasar::log::SimpleLogger::WARN;
seasar::container::S2ApplicationContext::import(dirname(__FILE__) . '/classes', array());
$container = seasar::container::S2ApplicationContext::create();

$bar = $container->getComponent('aa::bb::Bar');
var_dump($bar);

$foo = $container->getComponent('aa::bb::Foo');
var_dump($foo);

$hoge = $container->getComponent('aa::bb::Hoge');
var_dump($hoge);
