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
 */
abstract class S2Container_AbstractInterType implements S2Container_InterType {
    
    /** */
    protected $targetClass;
    /** */
    protected $enhancedClass;
    /** */
    protected $enhancedClassName;

    /**
     * 
     */
    public function __construct($enhancedClass){
        $this->enhancedClass = $enhancedClass;
    }

    /**
     * 
     */
    public function introduce(ReflectionClass $targetClass, $enhancedClassName) {
        $this->targetClass = $targetClass;
        $this->enhancedClassName = $enhancedClassName;
    }

    /**
     * 
     */
    public function getTargetClass() {
        return $this->targetClass;
    }
    
    /**
     * 
     */
    public function setTargetClass(ReflectionClass $targetClass){
        $this->targetClass = $targetClass;
    }
    
    /**
     * 
     */
    public function getEnhancedClass() {
        return $this->enhancedClass;
    }
    
    /**
     * 
     */
    public function setEnhancedClass($enhancedClass){
        $this->enhancedClass = $enhancedClass;
    }
    
    /**
     * 
     */
    public function getEnhancedClassName(){
        return $this->enhancedClassName;
    }
    
    /**
     * 
     */
    public function setEnhancedClassName($enhancedClassName){
        $this->enhancedClassName = $enhancedClassName;
    }

    /**
     * 
     */
    protected function addInterface($className) {
        $this->enhancedClass->addInterface($className);
    }

    /**
     * 
     */
    protected function addConstant($name, $value) {
        $this->enhancedClass->addConstant($name, $value);
    }

    /**
     * 
     */
    protected function addStaticProperty(array $modify, $name, $value = null) {
        $modify[] = self::STATIC_;
        $this->addProperty($modify, $name, $value);
    }

    /**
     * 
     */
    protected function addProperty(array $modify, $name, $value = null) {
        $this->enhancedClass->addProperty(implode('', $modify), $name, $value);
    }

    /**
     * 
     */
    protected function addStaticMethod(array $modify, $name, $src) {
        $modify[] = self::STATIC_;
        $this->addMethod($modify, $name, $src);
    }

    /**
     * 
     */
    protected function addMethod(array $modify, $name, $src) {
        $this->enhancedClass->addMethod(implode('', $modify), $name, $src);
    }

}

?>
