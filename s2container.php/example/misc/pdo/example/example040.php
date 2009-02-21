<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/example/CdDao2.php');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/db/sqlite_b.db';

s2aspect('pdo.interceptor', '/Dao2$/', '/^(insert|update|delete)/');

$dao = s2app::get('CdDao2');

$rows = $dao->sampleTransaction();

$rows = $dao->errorTransaction();

