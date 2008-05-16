<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use seasar::container::S2ApplicationContext as s2app;
s2app::registerAspect('/Dao$/', 'pdo.interceptor');
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/config');

$dao = s2app::get('sample::pdo::EmpDao');
$paginate = new Paginate;
$paginate->setLimit(5);
$rows = $dao->findByPaginate($paginate);
var_dump($rows);

$paginate->next();
$rows = $paginate->find($dao, 'findAll');
var_dump($rows);
