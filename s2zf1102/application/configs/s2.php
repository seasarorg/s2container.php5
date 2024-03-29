<?php
require_once('S2Container.php');

/** S2Log設定 */
\seasar\Config::$LOG_LEVEL = seasar\log\impl\SimpleLogger::WARN;
\seasar\Config::$DEBUG_EVAL = true;
\seasar\Config::$DEBUG_VERBOSE = false;
\seasar\Config::$SIMPLE_LOG_FILE = dirname(APPLICATION_PATH) . '/var/logs/s2.log';

/** S2Aop設定 */
\seasar\aop\Config::$CACHING = true;
\seasar\aop\Config::$CACHE_DIR = dirname(APPLICATION_PATH) . '/var/cache/s2aop';

