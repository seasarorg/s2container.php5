<?php
require_once(dirname(__FILE__) . '/../S2Container.php');
seasar::Config::$DEBUG_EVAL = true;

seasar::aop::Config::$CACHING = false;
seasar::aop::Config::$CACHE_DIR = dirname(__FILE__) . '/cache';
