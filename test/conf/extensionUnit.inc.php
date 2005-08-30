<?php
$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/unit/phpunit2",
    TEST_DIR . "/s2container.php5/org/seasar/extension/unit/phpunit",
    TEST_DIR . "/s2container.php5/org/seasar/extension/unit/simpletest"    
);

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
?>