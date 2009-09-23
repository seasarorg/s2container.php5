<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
s2aspect()
  ->setPattern('/Service$/')
  ->setPointcut('/^add/')
  ->setInterceptor(function($invoker) {
        return $invoker->proceed() * 1.05;
    });


$service = s2app::get('Service');
echo get_class($service) . PHP_EOL;
echo $service->add(2, 3) . PHP_EOL;
