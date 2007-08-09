<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php'); 

define('LOG4PHP_DIR', dirname(__FILE__) . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', dirname(__FILE__) . '/log4php.properties');
require_once(LOG4PHP_DIR . '/LoggerManager.php');


//S2Container_S2LogFactory::$LOGGER = S2Container_S2LogFactory::LOG4PHP;
S2Container_S2Logger::$LOGGER_FACTORY = 'S2Container_Log4phpLoggerFactory';

$logger = S2Container_S2Logger::getLogger('log4phpClient.php');
$logger->debug('test debug');
$logger->info ('test info ');
$logger->warn ('test warn ');
$logger->error('test error');
$logger->fatal('test fatal');
?>
