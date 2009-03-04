<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;
seasar\container\ComponentInfoDef::$USE_PHP_NAMESPACE = true;

s2app::import(dirname(__FILE__) . '/classes');
$container = s2app::create();

var_dump($container->hasComponentDef('a'));
var_dump($container->hasComponentDef('a.b'));
var_dump($container->hasComponentDef('a.c'));

var_dump($container->hasComponentDef('a.foo'));
var_dump($container->hasComponentDef('a.b.service'));
var_dump($container->hasComponentDef('a.b.Bar'));
var_dump($container->hasComponentDef('a.c.Huga'));


var_dump(s2app::hasComponentDef('Bar', 'a.b'));
$bar1 = s2app::get('Bar', 'a.b');
$bar2 = s2app::get('Bar', 'a.b');
var_dump($bar1 === $bar2);

var_dump(s2app::hasComponentDef('Bar', array('a.b', 'a.c')));
var_dump(s2app::hasComponentDef('Huga', array('a.b', 'a.c')));
$bar1 = s2app::get('Bar', array('a.b', 'a.c'));
$bar2 = s2app::get('Bar', array('a.c', 'a.b'));
var_dump($bar1 === $bar2);

