<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

class Action {}

s2component('Action');  // same as s2app::register('Action');

$action = s2get('Action');
var_dump($action);

$action = s2get('action');
var_dump($action);

s2init();


s2component('Action')->setName('act');
$action = s2get('act');
var_dump($action);

