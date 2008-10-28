<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar::container::S2ApplicationContext as s2app;

s2app::registerAspect('/Dao$/', 'pdo.interceptor');
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/config/SqliteAPdo.php');

$dao = s2app::get('sample::pdo::CdDao');
$rows = $dao->findAll();
var_dump($rows);

s2app::init();
s2app::registerAspect('/Dao$/', 'pdo.interceptor');
s2app::import(ROOT_DIR . '/classes');
StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sqlite_a.db';

$dao = s2app::get('sample::pdo::CdDao');
$rows = $dao->findAll();
var_dump($rows);

