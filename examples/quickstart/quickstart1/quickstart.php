<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

$container = S2ContainerApplicationContext::create();
$container->getComponentDefSize() == 0 ?
    print '����ƥʤϤ���äݤǤ���' . PHP_EOL:
    print '����ݡ��ͥ�Ȥ����äƤޤ���' . PHP_EOL;
