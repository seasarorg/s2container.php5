<?php
error_reporting(E_ALL);
define('HOME_DIR',dirname(dirname(dirname(__FILE__))));

require_once(HOME_DIR . '/S2Container.php'); 
S2ContainerClassLoader::import(S2CONTAINER_PHP5);
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}

define('S2AOP_PHP5_FILE_CACHE',false);
//define('S2AOP_PHP5_FILE_CACHE_DIR','./cache');
define('S2AOP_PHP5_FILE_CACHE_DIR',dirname(__FILE__).'/cache');
//define('S2AOP_PHP5_FILE_CACHE_DIR','./not_exists_cache');

require_once('classes.php');

$pointcut = new S2Container_PointcutImpl(array("test"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
$proxy = S2Container_AopProxyFactory::create(new A(),'A', array($aspect));
$proxy->test(2,3);

$proxy = S2Container_AopProxyFactory::create(new B(),'B', array($aspect));
$proxy->test(2,3);
?>
