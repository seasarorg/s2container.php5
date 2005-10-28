<?php
require_once(dirname(__FILE__) . '/db.inc.php');

$PATH = EXAMPLE_DIR . "/extension/db/txDeptDao.dicon";
$PATH = EXAMPLE_DIR . "/extension/db/txDeptDao.ini";

$container = S2ContainerFactory::create($PATH);
$dao = $container->getComponent('deptDao');
$result = $dao->findDeptByDeptno(10);
print_r($result);

?>