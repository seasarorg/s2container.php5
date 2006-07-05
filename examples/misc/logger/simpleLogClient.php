<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php'); 

define('S2CONTAINER_PHP5_LOG_LEVEL',S2Container_SimpleLogger::DEBUG);
$logger = S2Container_S2Logger::getLogger('simpleLogClient.php');
$logger->debug('test debug');
$logger->info ('test info ');
$logger->warn ('test warn ');
$logger->error('test error');
$logger->fatal('test fatal');
?>
