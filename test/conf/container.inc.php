<?php
$packages = array(
    TEST_DIR . '/s2container.php5/org/seasar/framework/aop',
    TEST_DIR . '/s2container.php5/org/seasar/framework/aop/proxy',
    TEST_DIR . '/s2container.php5/org/seasar/framework/aop/impl',
    TEST_DIR . '/s2container.php5/org/seasar/framework/aop/interceptors',
    TEST_DIR . '/s2container.php5/org/seasar/framework/util',
    TEST_DIR . '/s2container.php5/org/seasar/framework/beans',
    TEST_DIR . '/s2container.php5/org/seasar/framework/beans/factory',
    TEST_DIR . '/s2container.php5/org/seasar/framework/beans/impl',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container/assembler',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container/deployer',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container/impl',
    TEST_DIR . '/s2container.php5/org/seasar/framework/container/util',
    TEST_DIR . '/s2container.php5/org/seasar/framework/exception'
);

foreach($packages as $pkgDir){
    requireOnce($pkgDir);
}

/*
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .
         implode(PATH_SEPARATOR, $packages));
*/
?>