<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/extension/autoregister/aspectAnno.dicon";
$container = S2ContainerFactory::create($PATH);
$a = 1;
$b = 2;

define('S2CONTAINER_PHP5_ANNOTATION_HANDLER','S2Container_CommentAnnotationHandler');
$container->init();
$hoge = $container->getComponent('hoge');

print "$a + $b = " . $hoge->m1($a,$b) . "\n";
print "$a * $b = " . $hoge->m2($a,$b) . "\n";

?>
