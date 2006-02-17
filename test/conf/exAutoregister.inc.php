<?php
$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/autoregister" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/autoregister/factory",
    TEST_DIR . "/s2container.php5/org/seasar/extension/autoregister/impl",
    TEST_DIR . "/s2container.php5/org/seasar/extension/autoregister/util"
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
?>