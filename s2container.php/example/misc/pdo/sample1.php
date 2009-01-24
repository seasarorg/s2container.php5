<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;

s2app::import(ROOT_DIR . '/classes');
s2app::registerAspect('/Dao$/', 'pdo.interceptor');
StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sqlite_a.db';

$dao = s2app::get('sample\pdo\CdDao');
$rows = $dao->findAll();
var_dump($rows);

