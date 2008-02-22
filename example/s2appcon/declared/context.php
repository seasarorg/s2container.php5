<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
require_once(dirname(__FILE__) . '/classes.php');

seasar::container::S2ApplicationContext::$INCLUDE_DECLARED_CLASS = true;
seasar::container::S2ApplicationContext::addIncludePattern('/^a::b::/');
$container = seasar::container::S2ApplicationContext::create();
$container->hasComponentDef('Service') ? print 'has service.' . PHP_EOL : print 'none' . PHP_EOL;
$container->hasComponentDef('Logic') ? print 'has logic.' . PHP_EOL : print 'none' . PHP_EOL;
