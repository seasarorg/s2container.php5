<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

class Action {}

s2component('Action');

$action = s2app::get('Action');
var_dump($action);

$action = s2app::get('action');
var_dump($action);

s2app::init();


s2component('Action')->setName('act');
$action = s2app::get('act');
var_dump($action);

