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
 * @package org.seasar.framework.beans
 * @author klove
 */
interface S2Container_BeanDesc {

    public function getBeanClass();
    
    public function hasPropertyDesc($propertyName);

    public function getPropertyDesc($propertyName);
    
    public function getPropertyDescSize();
    
    public function hasField($fieldName);
    
    public function getField($fieldName);
    
    public function newInstance($args);
        
    public function getSuitableConstructor();
    
    public function invoke($target, $methodName,$args);
        
    public function getMethods($methodName);
    
    public function hasMethod($methodName);
        
    public function getMethodNames();

    public function hasConstant($constName);
    
    public function getConstant($constName);

}
?>
