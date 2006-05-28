<?php
require_once(dirname(__FILE__) . '/db.inc.php');

$PATH = EXAMPLE_DIR . "/extension/db/deptDao.dicon";

$container = S2ContainerFactory::create($PATH);
$dao = $container->getComponent('deptDao');
$result = $dao->findDeptByDeptno(10);
print_r($result);

?>