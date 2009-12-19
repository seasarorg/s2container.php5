<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Tokyo');

require_once(dirname(dirname(__FILE__)) . '/S2Erd.php');

\seasar\Config::$DEBUG_EVAL = true;
\seasar\Config::$LOG_LEVEL = seasar\log\impl\SimpleLogger::DEBUG;

echo PHP_EOL . 's2erd root dir : ' . S2ERD_ROOT_DIR . PHP_EOL . PHP_EOL;

