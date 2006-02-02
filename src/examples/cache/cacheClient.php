<?php
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 
$time_start = microtime_float();
$time_end = microtime_float();
$time = $time_end - $time_start;

require_once('example.inc.php');

$PATH_A = EXAMPLE_DIR . "/cache/testA.dicon";
$time_start = microtime_float();
$container = S2ContainerCachingFactory::create($PATH_A);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";


define('S2CONTAINER_CACHE_DIR',EXAMPLE_DIR . "/cache/var");

$PATH_B = EXAMPLE_DIR . "/cache/testB.dicon";
$time_start = microtime_float();
$container = S2ContainerCachingFactory::create($PATH_B);
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

$a = $container->getComponent("A");
print_r($a);

$PATH_C = EXAMPLE_DIR . "/cache/testC.dicon";
$time_start = microtime_float();
$container = S2ContainerCachingFactory::create($PATH_C,'testC.dat');
$time_end = microtime_float();
$time = $time_end - $time_start;
print "time : $time \n";

$a = $container->getComponent("A");
print_r($a);


class A{}

?>