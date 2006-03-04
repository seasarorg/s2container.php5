<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/autoregister/aspectAnno2.dicon";
$container = S2ContainerFactory::create($PATH);
$a = 1;
$b = 2;

$container->init();
$hoge = $container->getComponent('hogeChain');

print "$a + $b = " . $hoge->m1($a,$b) . "\n";
print "$a * $b = " . $hoge->m2($a,$b) . "\n";
print "$a - $b = " . $hoge->m3($a,$b) . "\n";
print "$a / $b = " . $hoge->m4($a,$b) . "\n";

?>
