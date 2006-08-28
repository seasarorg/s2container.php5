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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.aop.intertype
 * @author nowel
 * @version test
 */
abstract class S2Container_AbstractInterType implements S2Container_InterType {
    
    const COMPONENT = 'instance = prototype';
    protected $targetClass;
    protected $enhancedClass;
    protected $enhancedClassName;

    public function __construct($enhancedClass){
        $this->enhancedClass = $enhancedClass;
    }

    public function introduce(ReflectionClass $targetClass, $enhancedClassName) {
        $this->targetClass = $targetClass;
        $this->enhancedClassName = $enhancedClassName;
    }

    protected function getTargetClass() {
        return $this->targetClass;
    }

    protected function getEnhancedClass() {
        return $this->enhancedClass;
    }
    
    protected function getEnhancedClassName(){
        return $this->enhancedClassName;
    }

    protected function addInterface($className) {
        $this->enhancedClass->addInterface($className);
    }

    protected function addConstant($name, $value) {
        $this->enhancedClass->addConstant($name, $value);
    }

    protected function addStaticProperty($name) {
        $this->addProperty(self::STATIC_, $name);
    }

    protected function addProperty($modify, $name) {
        $this->enhancedClass->addProperty($modify, $name);
    }

    protected function addStaticMethod($name, $src) {
        $this->addMethod(self::STATIC_, $name, $src);
    }

    protected function addMethod($modify, $name, $src) {
        $this->enhancedClass->addMethod($modify, $name, $src);
    }

}

?>
