<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar\container\S2ApplicationContext as s2app;
s2app::import(dirname(__FILE__) . '/classes');

$service = new \ReflectionClass('Service');

$annotation = \seasar\util\Annotation::get($service, '@Sample');
print_r($annotation);

$annotation = \seasar\util\Annotation::get($service, 'SAMPLE');
print_r($annotation);
