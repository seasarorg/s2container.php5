<?php
error_reporting(E_ALL | E_STRICT);
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 

define('S2AOP_PHP5_FILE_CACHE',false);
//define('S2AOP_PHP5_FILE_CACHE_DIR','./var');
define('S2AOP_PHP5_FILE_CACHE_DIR',dirname(__FILE__).'/var');
//define('S2AOP_PHP5_FILE_CACHE_DIR','./not_exists_cache');

require_once('classes.php');

$pointcut = new S2Container_PointcutImpl(array("test"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
$proxy = S2Container_AopProxyFactory::create(new A(),'A', array($aspect));
$proxy->test(2,3);

$time_start = microtime_float();
$proxy = S2Container_AopProxyFactory::create(new B(),'B', array($aspect));
$time_end = microtime_float();
$time = $time_end - $time_start;
print "\ntime : $time \n";
$proxy->test(2,3);

?>
