<?php
$packages = array(
    TEST_DIR . '/sample/action',
    TEST_DIR . '/sample/action/impl',
    TEST_DIR . '/sample/logic',
    TEST_DIR . '/sample/logic/impl',
    TEST_DIR . '/sample/interface',
    TEST_DIR . '/sample',
    TEST_DIR . "/sample/db",
    TEST_DIR . "/sample/db/impl"
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}
/*    
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
*/
?>