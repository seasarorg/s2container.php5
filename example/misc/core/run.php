<?php
//require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/S2Container.php');
define('S2CONTAINER_ROOT_DIR', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
seasar\util\ClassLoader::$CLASSES = array();
seasar\util\ClassLoader::import(S2CONTAINER_ROOT_DIR . '/classes');

if (function_exists('spl_autoload_register')) {
    spl_autoload_register(array('seasar\util\ClassLoader', 'load'));
}
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/Config.php');
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/container/Config.php');
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/Config.php');

\seasar\container\S2ApplicationContext::import(dirname(__FILE__) . '/classes');
\seasar\container\S2ApplicationContext::registerAspect('/Service/', 'new seasar\aop\interceptor\TraceInterceptor');
$container = seasar\container\S2ApplicationContext::create();
$service  = $container->getComponent('Service');
$service->execute();
