<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;
s2app::import(dirname(__FILE__) . '/classes');
$service = s2app::get('Service');

print get_class($service->container) . PHP_EOL;
