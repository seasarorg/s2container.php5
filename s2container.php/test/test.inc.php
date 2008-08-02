<?php
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Asia/Tokyo');

require_once(dirname(dirname(__FILE__)) . '/S2Container.php');
//require_once('S2Container/S2Container.php');
//require_once(dirname(dirname(__FILE__)) . '/S2Container-2.0.0.phar');
seasar::Config::$DEBUG_EVAL = true;

seasar::aop::Config::$CACHING = false;
seasar::aop::Config::$CACHE_DIR = dirname(__FILE__) . '/cache';
