<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/extension/autoregister/methodAnno.dicon";
$container = S2ContainerFactory::create($PATH);

define('S2CONTAINER_PHP5_ANNOTATION_HANDLER','S2Container_CommentAnnotationHandler');
$container->init();
$hoge = $container->getComponent('hoge');
print_r($hoge);
?>
