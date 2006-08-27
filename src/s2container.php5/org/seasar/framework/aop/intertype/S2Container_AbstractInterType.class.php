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

    public function introduce($targetClass, $enhancedClassName) {
        $this->targetClass = $targetClass;
        $this->enhancedClassName = $enhancedClassName;
    }

    protected function getTargetClass() {
        return $this->targetClass;
    }

    protected function getEnhancedClass() {
        return $this->enhancedClass;
    }

    protected function getClassPool() {
        return $this->classPool;
    }

    protected function addInterface($clazz) {
        $this->enhancedClass->addInterface($this->toCtClass($clazz));
    }

    protected function addConstant($type, $name, $init) {
        $this->addStaticPropery(self::CONST_, $type, $name, $init);
    }

    protected function addStaticProperty() {
        $modify = func_get_arg(0);
        $access = func_get_arg(1);
        $name = func_get_arg(2);
        $this->addProperty(self::STATIC_ | $modify, $name);
    }

    protected function addProperty() {
        $modify = func_get_arg(0);
        $name = func_get_args(1);
        $this->enhancedClass->addProperty($modify, $name);
    }

    protected function addStaticMethod() {
        $returnref = false;
        $name = func_get_arg(0);
        $paramTypes = func_get_arg(1);
        $src = func_get_arg(2);
        $this->addMethod(self::PUBLIC_ | self::STATIC_, $returnref, $name, $paramTypes, $src);
    }

    protected function addMethod() {
        $modify = func_get_arg(0);
        $name = func_get_arg(1);
        $src = func_get_arg(2);
        $this->enhancedClass->addMethod($modify, $name, $src);
    }

}

?>
