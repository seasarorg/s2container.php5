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
 * PropertyDef�̐ݒ���T�|�[�g���܂��B
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_PropertyDefSupport {

    private $propertyDefs_ = array();
    private $propertyDefList_ = array();
    private $container_;

    public function S2Container_PropertyDefSupport() {
    }

    public function addPropertyDef(S2Container_PropertyDef $propertyDef) {
        if ($this->container_ != null) {
            $propertyDef->setContainer($this->container_);
        }
        $this->propertyDefs_[$propertyDef->getPropertyName()] = $propertyDef;
        array_push($this->propertyDefList_,$propertyDef->getPropertyName());
    }

    public function getPropertyDefSize() {
        return count($this->propertyDefs_);
    }

    public function getPropertyDef($propertyName) {
        if(is_int($propertyName)){
            return $this->propertyDefs_[$this->propertyDefList_[$propertyName]];
        }
        return $this->propertyDefs_[$propertyName];
    }
    
    public function hasPropertyDef($propertyName) {
        return array_key_exists($propertyName,$this->propertyDefs_);
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getPropertyDefSize(); ++$i) {
            $this->getPropertyDef($i)->setContainer($container);
        }
    }
}
?>