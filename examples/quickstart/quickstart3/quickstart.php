<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

S2ContainerApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
$container = S2ContainerApplicationContext::create();
$container->getComponentDefSize() == 0 ?
    print '����ƥʤϤ���äݤǤ���' . PHP_EOL:
    print '����ݡ��ͥ�Ȥ����äƤޤ���' . PHP_EOL;
$hello = $container->getComponent('Hello');
$hello->sayHello();
