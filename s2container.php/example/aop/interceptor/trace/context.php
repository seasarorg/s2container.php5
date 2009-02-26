<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/example.inc.php');

\seasar\container\S2ApplicationContext::import(dirname(__FILE__) . '/classes');
\seasar\container\S2ApplicationContext::registerAspect('new \seasar\aop\interceptor\TraceInterceptor', '/^Service$/');
$container = \seasar\container\S2ApplicationContext::create();
$service = $container->getComponent('Service');
print get_class($service) . PHP_EOL;
$result = $service->add(2, 3);
