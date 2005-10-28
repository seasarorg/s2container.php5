<?php
define('HOME_DIR',dirname(dirname(dirname(__FILE__))));
define('SRC_DIR',HOME_DIR . '/src');
define('TEST_DIR',HOME_DIR . '/test');

require_once(HOME_DIR . '/s2container.inc.php'); 

define('S2CONTAINER_PHP5_APP_DICON',TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/app.dicon');
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);

require_once(TEST_DIR . '/conf/simpletest.inc.php');
require_once(TEST_DIR . '/conf/sample.inc.php');

function requireOnce($dir){
    $d = dir($dir);
    while (false !== ($entry = $d->read())) {
        if(preg_match("/\.php$/",$entry)){
            $path = "$dir/$entry";
            require_once($path);
        }
    }
    $d->close();
}

?>