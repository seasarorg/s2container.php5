<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
\seasar\util\ClassLoader::import(dirname(__FILE__) . '/classes');
\seasar\util\Annotation::$CONSTANT = true;

$service = new \ReflectionClass('Service');

$annotation = \seasar\util\Annotation::get($service, '@Sample');
print_r($annotation);

$annotation = \seasar\util\Annotation::get($service, 'SAMPLE');
print_r($annotation);
