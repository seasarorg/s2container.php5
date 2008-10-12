<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar::container::S2ApplicationContext as s2app;
s2app::registerAspect('/Dao$/', 'pdo.interceptor', '/^(find|insert|update|delete)/');
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/config');

$dao = s2app::get('sample::pdo::CdDao');
$dao->transactionalInsert();