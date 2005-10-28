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
 * @package org.seasar.framework.beans.impl
 * @author klove
 */
final class S2Container_BeanDescImpl implements S2Container_BeanDesc {

    private static $EMPTY_ARGS = array();
    private $beanClass_;
    private $constructors_;
    private $propertyDescCache_ = array();
    private $propertyDescCacheIndex_ = array();
    private $methodsCache_ = array();
    private $fieldCache_ = array();
    private $constCache_ = array();
    
    public function S2Container_BeanDescImpl(ReflectionClass $beanClass) {
        $this->beanClass_ = $beanClass;
        $this->constructors_ = $this->beanClass_->getConstructor();
        $this->setupMethods();
        $this->setupPropertyDescs();
        $this->setupField();
        $this->setupConstant();
    }

    /**
     * @see S2Container_BeanDesc::getBeanClass()
     */
    public function getBeanClass() {
        return $this->beanClass_;
    }

    public function hasPropertyDesc($propertyName) {
        return array_key_exists($propertyName,$this->propertyDescCache_);
    }

    /**
     * @see S2Container_BeanDesc::getPropertyDesc()
     */
    public function getPropertyDesc($propertyName) {

        if(is_int($propertyName)){
            if ($propertyName >= count($this->propertyDescCacheIndex_)) {
                throw new S2Container_PropertyNotFoundRuntimeException(
                    $this->beanClass_, 'index '.$propertyName);
            }
            return $this->propertyDescCache_[
                       $this->propertyDescCacheIndex_[$propertyName]];
        }
        $pd = null;
        if(array_key_exists($propertyName,$this->propertyDescCache_)){
            $pd = $this->propertyDescCache_[$propertyName];
        }

        if ($pd == null) {
            throw new S2Container_PropertyNotFoundRuntimeException(
                $this->beanClass_, $propertyName);
        }
        
        return $pd;
    }
    
    private function getPropertyDesc0($propertyName) {
        if(array_key_exists($propertyName,$this->propertyDescCache_)){
            return $this->propertyDescCache_[$propertyName];
        }else{
            return null;
        }
    }

    /**
     * @see S2Container_BeanDesc::getPropertyDescSize()
     */
    public function getPropertyDescSize() {
        return count($this->propertyDescCache_);
    }
    
    /**
     * @see S2Container_BeanDesc::hasField()
     */
    public function hasField($fieldName) {
        return array_key_exists($fieldName,$this->fieldCache_);
    }
    
    /**
     * @see S2Container_BeanDesc::getField()
     */
    public function getField($fieldName) {
        if(array_key_exists($fieldName,$this->fieldCache_)){
            $field = $this->fieldCache_[$fieldName];
        }else{
            throw new S2Container_FieldNotFoundRuntimeException($this->beanClass_, $fieldName);
        }
        return $field;
    }

    /**
     * @see S2Container_BeanDesc::hasConstant()
     */
    public function hasConstant($constName) {
        return array_key_exists($constName,$this->constCache_);
    }
    
    /**
     * @see S2Container_BeanDesc::getConstant()
     */
    public function getConstant($constName) {
        if(array_key_exists($constName,$this->constCache_)){
            $constant = $this->constCache_[$constName];
        }else{
            throw new S2Container_ConstantNotFoundRuntimeException($this->beanClass_, $constName);
        }
        return $constant;
    }

    /**
     * @see S2Container_BeanDesc::newInstance()
     */
    public function newInstance($args,$componentDef=null){
        if($componentDef != null and 
           $componentDef->getAspectDefSize()>0){
            return S2Container_AopProxyUtil::getProxyObject($componentDef,$args); 
        }
        return S2Container_ConstructorUtil::newInstance($this->beanClass_, $args);
    }

    /**
     * @see S2Container_BeanDesc::invoke()
     */
    public function invoke($target,$methodName,$args) {
        $method = $this->getMethods($methodName);
        return S2Container_MethodUtil::invoke($method,$target,$args);
    }

    /**
     * @see S2Container_BeanDesc::getSuitableConstructor()
     */
    public function getSuitableConstructor() {
    	return $this->constructors_;
    }
    
    /**
     * @see S2Container_BeanDesc::getMethods()
     */
    public function getMethods($methodName){

        if(array_key_exists($methodName,$this->methodsCache_)){
            $methods = $this->methodsCache_[$methodName];
        }else{
            throw new S2Container_MethodNotFoundRuntimeException($this->beanClass_, $methodName, null);
        }
        return $methods;
    }
    
    /**
     * @see S2Container_BeanDesc::hasMethod()
     */
    public function hasMethod($methodName) {
        if(array_key_exists($methodName,$this->methodsCache_)){
            return $this->methodsCache_[$methodName] != null;
        }else{
            return false;
        }
    }
    
    public function getMethodNames() {
        return array_keys($this->methodsCache_);
    }

    private function isFirstCapitalize($str){
        $top = substr($str,0,1);
        $upperTop = strtoupper($top);
        return $upperTop == $top;
    }
    
    private function setupPropertyDescs() {
        $methods = $this->beanClass_->getMethods();
        for ($i = 0; $i < count($methods); $i++) {
            $mRef = $methods[$i];
            $methodName = $mRef->getName();
            if (preg_match("/^get(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 0){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName = 
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupReadMethod($mRef, $propertyName);
            } else if (preg_match("/^is(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 0){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName =
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupReadMethod($mRef, $propertyName);
            } else if (preg_match("/^set(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 1){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName =
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupWriteMethod($mRef, $propertyName);
            } else if (preg_match("/^(__set)$/",$methodName,$regs)) {
                $propertyName = $regs[1];
                $this->setupWriteMethod($mRef, $propertyName);
            }
        }
    }

    private function decapitalizePropertyName($name) {
        $top = substr($name,0,1);
        $top = strtolower($top);
        return substr_replace($name,$top,0,1);
    }

    private function addPropertyDesc(S2Container_PropertyDesc $propertyDesc) {
        $this->propertyDescCache_[$propertyDesc->getPropertyName()] = $propertyDesc;
        array_push($this->propertyDescCacheIndex_,$propertyDesc->getPropertyName());
    }

    private function setupReadMethod($readMethod, $propertyName) {
		$propDesc = $this->getPropertyDesc0($propertyName);
		if ($propDesc != null) {
			$propDesc->setReadMethod($readMethod);
		} else {
    		$writeMethod = null;	
			$propDesc =
				new S2Container_PropertyDescImpl(
					$propertyName,
					null,
					$readMethod,
					null,
					$this);
					
			$this->addPropertyDesc($propDesc);
		}
    }

    /**
     * @param ReflectionMethod writeMethod
     * @param string propertyName
     */
    private function setupWriteMethod($writeMethod,$propertyName) {
        $propDesc = $this->getPropertyDesc0($propertyName);
        if ($propDesc != null) {
            $propDesc->setWriteMethod($writeMethod);
            
        } else {
            if($propertyName == "__set"){
                $propDesc =
                    new S2Container_UuSetPropertyDescImpl(
                        $propertyName,
                        null,
                        null,
                        $writeMethod,
                        $this);
            }else{
                $propertyTypes = $writeMethod->getParameters();
                $propDesc =
                    new S2Container_PropertyDescImpl(
                        $propertyName,
                        $propertyTypes[0]->getClass(),
                        null,
                        $writeMethod,
                        $this);
            }
            $this->addPropertyDesc($propDesc);
        }       
    }

    private function setupMethods() {
        $methods = $this->beanClass_->getMethods();
        for ($i = 0; $i < count($methods); $i++) {
            $this->methodsCache_[$methods[$i]->getName()] = $methods[$i];
        }
    }
    
    private function setupField() {
        $fields = $this->beanClass_->getProperties();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i]->isStatic()) {
                $this->fieldCache_[$fields[$i]->getName()] = $fields[$i];
            }
        }
    }

    private function setupConstant() {
        $this->constCache_ = $this->beanClass_->getConstants();
    }
}
?>