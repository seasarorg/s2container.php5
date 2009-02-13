<?php
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'classes');
s2app::import(ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
s2app::registerAspect('/Dao$/', 'pdo.interceptor');


