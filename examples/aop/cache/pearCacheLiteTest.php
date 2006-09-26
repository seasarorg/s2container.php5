<?php
error_reporting(E_ALL | E_STRICT);
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::INFO);
S2Container_CacheSupportFactory::$SUPPORT_CLASS_NAME = "S2Container_PearCacheLiteSupport";

require_once('classes.php');

$pointcut = new S2Container_PointcutImpl(array("test"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);

$time_start = microtime_float();
$proxy = S2Container_AopProxyFactory::create(new A(),'A', array($aspect));
$time_end = microtime_float();
$time = $time_end - $time_start;
print "\ntime : $time \n";
$proxy->test(2,3);

S2Container_PearCacheLiteSupport::$AOP_PROXY_OPTIONS = array(
    'cacheDir' => dirname(__FILE__).'/var/');

$time_start = microtime_float();
$proxy = S2Container_AopProxyFactory::create(new B(),'B', array($aspect));
$time_end = microtime_float();
$time = $time_end - $time_start;
print "\ntime : $time \n";
$proxy->test(2,3);
?>
