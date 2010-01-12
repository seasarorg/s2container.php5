<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\util\ClassLoader as s2loader;

s2loader::import(dirname(__FILE__) . '/classes');

s2import(dirname(__FILE__) . '/quickstart050.dicon');
$action = s2get('Action');
$action->indexAction();
