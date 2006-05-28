<?php
require_once('../../example.inc.php');

define('LOG4PHP_DIR', dirname(__FILE__) . '/log4php');
define('LOG4PHP_CONFIGURATION', dirname(__FILE__) . '/log4php.properties');
require_once(LOG4PHP_DIR . '/LoggerManager.php');


S2Container_S2LogFactory::$LOGGER = S2Container_S2LogFactory::LOG4PHP;

$logger = S2Container_S2Logger::getLogger('log4phpClient.php');
$logger->debug('test debug');
$logger->info ('test info ');
$logger->warn ('test warn ');
$logger->error('test error');
$logger->fatal('test fatal');
?>
