<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
final class S2Container_S2Logger
{
    private static $loggerMap_ = array();

    private $log_;

    /**
     * @param string class name
     */
    private function __construct($className)
    {
        $this->log_ = S2Container_S2LogFactory::getLog($className);
    }

    /**
     * @param string class name 
     */
    public static final function getLogger($className)
    {
        if (!array_key_exists($className,S2Container_S2Logger::$loggerMap_)) {
            S2Container_S2Logger::$loggerMap_[$className] = 
                new S2Container_S2Logger($className);
        }
        return S2Container_S2Logger::$loggerMap_[$className]->_getLog();

    }
    
    /**
     * 
     */
    private function _getLog()
    {
        return $this->log_;
    }
}
?>
