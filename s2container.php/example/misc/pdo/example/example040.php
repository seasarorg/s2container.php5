<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/example/CdDao.php');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_b.db';

s2aspect('pdo.interceptor', '/Dao$/', '/^(insert|update|delete)/');

$dao = s2app::get('CdDao');

$rows = $dao->sampleTransaction();

$rows = $dao->errorTransaction();

