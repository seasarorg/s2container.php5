<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

seasar::container::S2ApplicationContext::import(dirname(__FILE__) . '/classes');
$container = seasar::container::S2ApplicationContext::create();
$action = $container->getComponent('Action');
var_dump($action);
