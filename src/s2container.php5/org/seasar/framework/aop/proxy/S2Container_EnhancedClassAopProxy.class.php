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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id: S2Container_DefaultAopProxy.class.php 355 2006-08-28 17:15:42Z nowel $
/**
 * @package org.seasar.framework.aop.proxy
 * @author nowel
 */
class S2Container_EnhancedClassAopProxy {

    /**
     * Object
     */
    public $target_ = null;
    
    /**
     * ReflectionClass 
     */
    public $targetClass_ = null;
    
    /**
     * 
     */
    private $methodInterceptorsMap_ = array();
    
    /**
     * 
     */
    private $parameters_ = array();
    
    /**
     * @param object target object
     * @param ReflectionClass target ReflectionClass
     * @param array interceptor map
     * @param array parameters
     */
    public function __construct($target, $targetClass,
                                $methodInterceptorsMap, $parameters = array())
    {
        $this->target_ = $target;
        $this->targetClass_ = $targetClass;
        $this->methodInterceptorsMap_ = $methodInterceptorsMap;
        $this->parameters_ = $parameters;
        $this->parameters_[S2Container_ContainerConstants::S2AOP_PROXY_NAME] = $this;
    }
    
    /**
     * @param $propertyName propertyName
     */
    public function __get($propertyName){
        if($this->targetClass_->hasProperty($propertyName)){
            $property = $this->targetClass_->getProperty($propertyName);
            return $property->getValue($this->target_);
        }
        throw new S2Container_S2RuntimeException('ESSR0065',
                    array($propertyName, $this->targetClass_->getName()));
    }
    
    /**
     * @param $propertyName propertyName
     * @param $value setValue
     */
    public function __set($propertyName, $value){
        if($this->targetClass_->hasProperty($propertyName)){
            $property = $this->targetClass_->getProperty($propertyName);
            return $property->setValue($this->target_, $value);
        }
        throw new S2Container_S2RuntimeException('ESSR0065',
                    array($propertyName, $this->targetClass_->getName()));
    }
    
    /**
     * @param string Method name
     * @param array Args 
     */
    public function __call($name,$args)
    {
        if (array_key_exists($name,$this->methodInterceptorsMap_)) {
            $methodInvocation = 
                new S2Container_S2MethodInvocationImpl($this->target_,
                                    $this->targetClass_,
                                    $this->targetClass_->getMethod($name),
                                    $args,
                                    $this->methodInterceptorsMap_[$name],
                                    $this->parameters_);
            return $methodInvocation->proceed();
        } else {
            if (!is_object($this->target_)) {
                throw new S2Container_S2RuntimeException('ESSR1009',
                    array($name,$this->targetClass_->getName()));
            }
            return S2Container_MethodUtil::invoke($this->targetClass_->getMethod($name),
                                                  $this->target_,
                                                  $args);
        }
    }
}
?>
