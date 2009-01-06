<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar::container::S2ApplicationContext as s2app;
s2app::registerAspect('/Dao$/', 'pdo.interceptor');
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/config');

$dao = s2app::get('sample::pdo::CdDao');
$rows = $dao->findAllFromB();
var_dump($rows);

$rows = $dao->findAllFromBwithSampleDto();
var_dump($rows);
echo 'id      = ' . $rows[0]->getId() . PHP_EOL;
echo 'title   = ' . $rows[0]->getTitle() . PHP_EOL;
echo 'content = ' . $rows[0]->getContent() . PHP_EOL;

