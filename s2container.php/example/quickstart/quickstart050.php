<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;
use seasar\util\ClassLoader as s2loader;

s2loader::import(dirname(__FILE__) . '/classes');

s2app::import(dirname(__FILE__) . '/quickstart050.dicon');
$action = s2app::get('Action');
$action->indexAction();
