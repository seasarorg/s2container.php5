<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

s2import(dirname(__FILE__) . '/classes');
$action = s2get('Action');
$action->getById();
