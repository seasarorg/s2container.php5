<?php
require_once(dirname(__FILE__) . '/throws.inc.php');

$PATH = EXAMPLE_DIR . "/aop/throwsinterceptor/Checker.dicon";
$PATH = EXAMPLE_DIR . "/aop/throwsinterceptor/Checker.ini";

$container = S2ContainerFactory::create($PATH);
$checker = $container->getComponent('Checker');
try{
    $checker->check("foo");
}catch(Exception $e){
    print "Exception : " . $e->getMessage() . "\n";
}

try{
    $checker->check(null);
}catch(Exception $e){
    print "Exception : " . $e->getMessage() . "\n";
}

try{
    $checker->check("hoge");
}catch(Exception $e){
    print "Exception : " . $e->getMessage() . "\n";
}
?>