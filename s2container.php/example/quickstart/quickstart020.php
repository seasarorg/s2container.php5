<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
$action = s2app::get('Action');
echo get_class($action) . PHP_EOL;

$action = s2app::get('action');
echo get_class($action) . PHP_EOL;

$action = s2app::get('act');
echo get_class($action) . PHP_EOL;

