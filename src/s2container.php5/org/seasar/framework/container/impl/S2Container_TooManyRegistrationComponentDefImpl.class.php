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
 * 1�̃L�[�ɕ����̃R���|�[�l���g���o�^���ꂽ�ꍇ�Ɏg�p����܂��B
 * 
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_TooManyRegistrationComponentDefImpl extends S2Container_SimpleComponentDef
                          implements S2Container_TooManyRegistrationComponentDef{

    private $key_;
    private $componentDefs_ = array();

    public function S2Container_TooManyRegistrationComponentDefImpl($key) {
        $this->key_ = $key;
    }

    public function addComponentDef($componentDef) {
        array_push($this->componentDefs_,$componentDef);
    }

    public function getComponentDefSize() {
        return count($this->componentDefs_);
    }
    
    /**
     * @see S2Container_ComponentDef::getComponent()
     */
    public function getComponent() {

        throw new S2Container_TooManyRegistrationRuntimeException(
            $this->key_,
            $this->getComponentClasses());
    }

    public function getComponentDefs() {
        return $this->componentDefs_;
    }

    public function getComponentClasses() {
        $classes = array();
        $size = $this->getComponentDefSize();
        foreach($this->componentDefs_ as $componentDef) {
            array_push($classes,$componentDef->getComponentClass());
        }
        return $classes;
    }
}
?>