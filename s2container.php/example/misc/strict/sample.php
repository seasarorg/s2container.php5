<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
s2app::registerAspect('/Hoge/', 'StrictInterceptor');
$hoge = s2app::get('Hoge');

$obj = $hoge->foo(1, 'abc');
$obj = $hoge->bar(new sample\Huga, 100);

try {
    $obj = $hoge->foo(1, 2);
} catch(StrictException $e) {
    print $e->getMessage() . PHP_EOL;
}
