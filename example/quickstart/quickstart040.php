<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

s2app::import(dirname(__FILE__) . '/classes');
$action = s2app::get('Action');
$action->getById();
