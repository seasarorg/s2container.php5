<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.log
 * @author klove
 */
final class S2Logger {

    private static $loggerMap_ = array();

    private $log_;

    private function S2Logger($className) {
        $this->log_ = S2LogFactory::getLog($className);
    }

    public static final function getLogger($className) {
        
        $logger = null;
        if(array_key_exists($className,S2Logger::$loggerMap_)){
            $logger = S2Logger::$loggerMap_[$className];
        }
        if ($logger == null) {
            $logger = new S2Logger($className);
            S2Logger::$loggerMap_[$className] = $logger;
        }
        return $logger->getLog();
    }
    
    private function getLog(){
        return $this->log_;
    }
}
?>