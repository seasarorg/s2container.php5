<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

$container = seasar::container::S2ApplicationContext::create();
$container->getComponentDefSize() == 0 ?
    print 'コンテナはからっぽです。' . PHP_EOL:
    print 'コンポーネントが入ってます。' . PHP_EOL;
