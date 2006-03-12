<?php
require_once(dirname(__FILE__) . '/trace.inc.php');

$pointcut = new S2Container_PointcutImpl(array("getTime"));
$aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
$proxy = S2Container_AopProxyFactory::create(new Date(),'Date', array($aspect));
$proxy->getTime();
?>
