<?php
$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/factory",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/reader",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/reader/impl",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/container",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/util",
    TEST_DIR . "/s2container.php5/org/seasar/extension/annotation/autoregister"
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
?>