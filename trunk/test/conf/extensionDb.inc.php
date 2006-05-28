<?php
$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/" ,
    TEST_DIR . "/s2container.php5/org/seasar/extension/db/pdo"
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
?>