<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.framework.util
 * @author klove
 */
final class S2Container_StringUtil
{
    /**
     * 
     */
    private function __construct() 
    {
    }
    
    /**
     * @param string
     */
    public static function expandPath($path)
    {
        if (preg_match('/^\%([0-9a-zA-Z_]+)\%(.*)/',$path,$regs)) {
            $prefix = $regs[1];
            $expand = "return " . $regs[1] . ". '" . $regs[2] . "';";

            S2Container_S2Logger::getLogger(__CLASS__)->
                debugEval("[ $expand ]",__METHOD__);
            $path = eval($expand);
        }    
        
        return $path;
    } 
}
?>
