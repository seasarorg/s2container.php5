<?php

//define('S2CONTAINER_ANNOTATION_READER','S2Container_CommentAnnotationReader');

$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/factory",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/reader",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/reader/impl",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/container"
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
?>