<?php
require_once('cacheClientCommons.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
$diconPath = dirname(__FILE__) . '/sample.dicon';
$time_start = microtime_float();
$container = S2ContainerFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

define('S2CONTAINER_PHP5_CACHE_LITE_INI', dirname(__FILE__) . '/cacheLite.ini');
$time_start = microtime_float();
$container = S2ContainerCacheFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

$time_start = microtime_float();
$bar = $container->getComponent('Bar');
$bar->test();
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

?>
