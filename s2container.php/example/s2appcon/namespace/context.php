<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

\seasar\container\S2ApplicationContext::import(dirname(__FILE__) . '/classes');
$container = \seasar\container\S2ApplicationContext::create();
$container->hasComponentDef('a') ? print 'has a.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('a.b') ? print 'has b.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('a.b.Service') ? print 'has service.' . PHP_EOL : print 'none' . PHP_EOL;

$container = \seasar\container\S2ApplicationContext::create('a');
$container->hasComponentDef('a') ? print 'has a.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('b') ? print 'has b.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('b.Service') ? print 'has service.' . PHP_EOL : print 'none' . PHP_EOL;

$container = \seasar\container\S2ApplicationContext::create('a.b');
$container->hasComponentDef('b.Service') ? print 'has service.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('Service') ? print 'has service.' . PHP_EOL : print 'none' . PHP_EOL;
