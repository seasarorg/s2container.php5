<?php
require_once('cacheClientCommons.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);
$diconPath = dirname(__FILE__) . '/sample.dicon';
$time_start = microtime_float();
$container = S2ContainerFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "no   cache time : $time \n";

define('S2CONTAINER_PHP5_CACHE_SUPPORT_CLASS', 'S2Container_FileCacheSupport');
$time_start = microtime_float();
$container = S2ContainerCacheFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "no   cache time : $time \n";

define('S2CONTAINER_PHP5_CACHE_DIR',dirname(__FILE__) . "/var");
$time_start = microtime_float();
$container = S2ContainerCacheFactory::create($diconPath);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "with cache time : $time \n";
?>
