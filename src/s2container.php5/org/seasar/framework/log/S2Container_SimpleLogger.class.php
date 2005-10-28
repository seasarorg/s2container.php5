<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.log
 * @author klove
 */
class S2Container_SimpleLogger {

    private $className;
    const DEBUG = 1;
    const INFO  = 2;
    const WARN  = 3;
    const ERROR = 4;
    const FATAL = 5;
    
    function S2Container_SimpleLogger($className) {
        $this->className = $className;
        if(!defined('S2CONTAINER_PHP5_LOG_LEVEL')){
            define('S2CONTAINER_PHP5_LOG_LEVEL',3);
        }
    }
    
    private function cli($level,$msg="",$methodName=""){
        switch($level){
            case S2Container_SimpleLogger::DEBUG :
                $logLevel = "DEBUG";
                break;    
            case S2Container_SimpleLogger::INFO :
                $logLevel = "INFO";
                break;    
            case S2Container_SimpleLogger::WARN :
                $logLevel = "WARN";
                break;    
            case S2Container_SimpleLogger::ERROR :
                $logLevel = "ERROR";
                break;    
            case S2Container_SimpleLogger::FATAL :
                $logLevel = "FATAL";
                break;    
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL <= $level){
            printf("[%-5s] %s - %s\n",$logLevel,$methodName,$msg);
        }
    }
    
    public function debug($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::DEBUG,$msg,$methodName);
    }

    public function info($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::INFO,$msg,$methodName);
    }

    public function warn($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::WARN,$msg,$methodName);
    }

    public function error($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::ERROR,$msg,$methodName);
    }

    public function fatal($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::FATAL,$msg,$methodName);
    }
}
?>