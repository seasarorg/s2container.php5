<?php
require_once('cacheClientCommons.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
define('S2CONTAINER_PHP5_CACHE_SUPPORT_CLASS', 'S2Container_ZendCacheSupport');

define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
$diconPath = dirname(__FILE__) . '/sample.dicon';

/*
$time_start = microtime_float();
$container = S2ContainerFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";
*/

define('S2CONTAINER_PHP5_ZEND_CACHE_INI', dirname(__FILE__) . '/zendCache.ini');
$time_start = microtime_float();
$container = S2ContainerFactory::create($diconPath);
$ref = new ReflectionClass('Zend_Cache');
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

$time_start = microtime_float();
$bar = $container->getComponent('Bar');
$bar->test();
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

print get_class(S2Container_CacheSupportFactory::create()) . PHP_EOL;
?>
