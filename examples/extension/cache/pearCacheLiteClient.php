<?php
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 
$time_start = microtime_float();
$time_end = microtime_float();
$time = $time_end - $time_start;

require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);
define('S2CONTAINER_PHP5_CACHE_SUPPORT_CLASS', 'S2Container_PearCacheLiteSupport');
//define('S2CONTAINER_PHP5_CACHE_LITE_INI', EXAMPLE_DIR . '/extension/cache/cacheLiteNoFile.ini');
define('S2CONTAINER_PHP5_CACHE_LITE_INI', EXAMPLE_DIR . '/extension/cache/cacheLite.ini');
$PATH_A = EXAMPLE_DIR . "/extension/cache/testA.dicon";

$time_start = microtime_float();
$container = S2ContainerCacheFactory::create($PATH_A);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

//S2Container_PearCacheLiteSupport::$CONTAINER_OPTIONS = array(
//    'cacheDir' => EXAMPLE_DIR . '/extension/cache/var/');


$PATH_B = EXAMPLE_DIR . "/extension/cache/testB.dicon";
$time_start = microtime_float();
$container = S2ContainerCacheFactory::create($PATH_B);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

$a = $container->getComponent("a");
print_r($a);

class A{}

?>