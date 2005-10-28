<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
define('UNIT_EXAMPLE', EXAMPLE_DIR . '/extension/unit');
define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);

require_once(UNIT_EXAMPLE . '/src/bar/BarLogic.class.php');
require_once(UNIT_EXAMPLE . '/src/foo/FooLogic.class.php');
?>
