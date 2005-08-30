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
class SimpleLogger {

    private $className;
    const DEBUG = 1;
    const INFO  = 2;
    const WARN  = 3;
    const ERROR = 4;
    const FATAL = 5;
    
    function SimpleLogger($className) {
        $this->className = $className;
        if(!defined('S2CONTAINER_PHP5_LOG_LEVEL')){
            define('S2CONTAINER_PHP5_LOG_LEVEL',3);
        }
    }
    
    private function cli($level,$msg="",$methodName=""){
        switch($level){
            case SimpleLogger::DEBUG :
                $logLevel = "DEBUG";
                break;    
            case SimpleLogger::INFO :
                $logLevel = "INFO";
                break;    
            case SimpleLogger::WARN :
                $logLevel = "WARN";
                break;    
            case SimpleLogger::ERROR :
                $logLevel = "ERROR";
                break;    
            case SimpleLogger::FATAL :
                $logLevel = "FATAL";
                break;    
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL <= $level){
            printf("[%-5s] %s - %s\n",$logLevel,$methodName,$msg);
        }
    }
    
    public function debug($msg="",$methodName=""){
        $this->cli(SimpleLogger::DEBUG,$msg,$methodName);
    }

    public function info($msg="",$methodName=""){
        $this->cli(SimpleLogger::INFO,$msg,$methodName);
    }

    public function warn($msg="",$methodName=""){
        $this->cli(SimpleLogger::WARN,$msg,$methodName);
    }

    public function error($msg="",$methodName=""){
        $this->cli(SimpleLogger::ERROR,$msg,$methodName);
    }

    public function fatal($msg="",$methodName=""){
        $this->cli(SimpleLogger::FATAL,$msg,$methodName);
    }
}
?>