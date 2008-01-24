<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/example.inc.php');

seasar::container::S2ApplicationContext::import(dirname(__FILE__) . '/classes');
seasar::container::S2ApplicationContext::registerAspect('/^Service$/', 'new seasar::aop::interceptor::MockInterceptor');
$container = seasar::container::S2ApplicationContext::create();
$service = $container->getComponent('Service');

print $service->add(2, 3) . PHP_EOL;
try {
    $result = $service->sub(3, 2);
} catch(Exception $e) {
    print get_class($e) . ' : ' . $e->getMessage() . PHP_EOL;
}

