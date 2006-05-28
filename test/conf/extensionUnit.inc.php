<?php
define('PHPUnit2_MAIN_METHOD', '');
require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

$packages = array(
    TEST_DIR . "/s2container.php5/org/seasar/extension/unit/phpunit2",
    TEST_DIR . "/s2container.php5/org/seasar/extension/unit/simpletest"    
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
/*
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
*/
?>