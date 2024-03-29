<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Tokyo');

require_once(dirname(dirname(__FILE__)) . '/S2Container.php');
//require_once('S2Container.php');
//require_once('phar://' . dirname(dirname(__FILE__)) . '/S2Container-2.0.3.phar');

\seasar\Config::$DEBUG_EVAL = true;
\seasar\Config::$LOG_LEVEL = seasar\log\impl\SimpleLogger::DEBUG;
\seasar\aop\Config::$CACHING = false;
\seasar\aop\Config::$CACHE_DIR = dirname(__FILE__) . '/cache';

echo PHP_EOL . 's2container root dir : ' . S2CONTAINER_ROOT_DIR . PHP_EOL . PHP_EOL;
