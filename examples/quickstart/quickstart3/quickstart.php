<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

S2ContainerApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
$container = S2ContainerApplicationContext::create();
$container->getComponentDefSize() == 0 ?
    print 'コンテナはからっぽです。' . PHP_EOL:
    print 'コンポーネントが入ってます。' . PHP_EOL;
$hello = $container->getComponent('Hello');
$hello->sayHello();
