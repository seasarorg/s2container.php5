<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

class Action {}

s2component('Action');  // same as s2app::register('Action');

$action = s2app::get('Action');
var_dump($action);

$action = s2app::get('action');
var_dump($action);

s2app::init();


s2component('Action')->setName('act');
$action = s2app::get('act');
var_dump($action);

