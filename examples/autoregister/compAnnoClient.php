<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');

$PATH = EXAMPLE_DIR . "/autoregister/compAnno.dicon";
$container = S2ContainerFactory::create($PATH);

//define('S2CONTAINER_PHP5_ANNOTATION_HANDLER','S2Container_CommentAnnotationHandler');
$container->init();
$hoge = $container->getComponent('hogeComp');
print_r($hoge);

?>
