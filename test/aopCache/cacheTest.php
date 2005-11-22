<?php
define('HOME_DIR',dirname(dirname(dirname(__FILE__))));

require_once(HOME_DIR . '/s2container.inc.php'); 
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}

define('S2AOP_PHP5_FILE_CACHE',false);
define('S2AOP_PHP5_FILE_CACHE_DIR','./cache');
//define('S2AOP_PHP5_FILE_CACHE_DIR',dirname(__FILE__).'/cache');
//define('S2AOP_PHP5_FILE_CACHE_DIR','./not_exists_cache');

require_once('classes.php');

$pointcut = new S2Container_PointcutImpl(array("test"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
$proxy = S2Container_AopProxyFactory::create(new A(),'A', array($aspect));
$proxy->test(2,3);

$proxy = S2Container_AopProxyFactory::create(new B(),'B', array($aspect));
$proxy->test(2,3);

/*
$proxy = S2Container_AopProxyFactory::create(null,'IB', array($aspect));
try{
    $proxy->test(2,3);
}catch(Exception $e){
    print "{$e->getMessage()}\n";
}

$pointcut = new S2Container_PointcutImpl(array("test2"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
$proxy = S2Container_AopProxyFactory::create(null,'A', array($aspect));
try{
    $proxy->test(2,3);
}catch(Exception $e){
    print "{$e->getMessage()}\n";
}
*/
?>