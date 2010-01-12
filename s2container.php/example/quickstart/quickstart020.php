<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

s2import(dirname(__FILE__) . '/classes');
$action = s2get('Action');
echo get_class($action) . PHP_EOL;

$action = s2get('action');
echo get_class($action) . PHP_EOL;

$action = s2get('act');
echo get_class($action) . PHP_EOL;

