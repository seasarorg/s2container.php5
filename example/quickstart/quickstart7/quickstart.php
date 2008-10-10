<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

seasar::container::S2ApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
seasar::container::S2ApplicationContext::registerAspect('/Printer$/', 'new seasar::aop::interceptor::TraceInterceptor', '/printOut/');
$container = seasar::container::S2ApplicationContext::create();
$hello = $container->getComponent('Hello');
$hello->sayHello();
