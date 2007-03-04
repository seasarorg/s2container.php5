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
class S2Container_S2LogFactory
{
    const SIMPLE = 'simple';
    const LOG4PHP = 'log4php';
    public static $LOGGER = self::SIMPLE;

    /**
     * 
     */
    private function __construct()
    {
    }
    
    /**
     * @param string class name
     * @return object logger
     */
    public static function getLog($className)
    {
        if (self::$LOGGER == self::LOG4PHP) {
            return LoggerManager::getLogger($className);
        } else {
            return new S2Container_SimpleLogger($className);
        }
    }
}
?>
