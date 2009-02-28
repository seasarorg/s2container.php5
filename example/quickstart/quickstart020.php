<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
$action = s2app::get('Action');
var_dump($action);

$action = s2app::get('action');
var_dump($action);

$action = s2app::get('act');
var_dump($action);

