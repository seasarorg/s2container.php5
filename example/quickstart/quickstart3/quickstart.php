<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

\seasar\container\S2ApplicationContext::import(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes');
$container = \seasar\container\S2ApplicationContext::create();
$container->getComponentDefSize() == 0 ?
    print 'コンテナはからっぽです。' . PHP_EOL:
    print 'コンポーネントが入ってます。' . PHP_EOL;
$hello = $container->getComponent('Hello');
$hello->sayHello();
