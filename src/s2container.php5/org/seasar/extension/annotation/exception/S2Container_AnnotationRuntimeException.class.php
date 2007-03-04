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
 * @package org.seasar.extension.annotation.exception
 * @author klove
 */
class S2Container_AnnotationRuntimeException 
    extends Exception 
{
    /**
     * 
     */
    public function __construct(
        $messageCode,
        $args = null,
        $cause = null)
    {

        switch ($messageCode) {
            case 'ERR001':
                $msg = "UnsupportedOperationException";
                break;
            case 'ERR002':
                $msg = "annotation [ {$args[0]} ] not found in class [ {$args[1]} ], method [ {$args[2]} ]";
                break;
            case 'ERR003':
                $msg = "array and hash can not mix. [ {$args[0]} ] [ {$args[1]} ]";
                break;
            case 'ERR004':
                $msg = "empty property name. [ {$args[0]} ] [ {$args[1]} ]";
                break;
        }
        
        parent::__construct($msg);
    }
}
?>
