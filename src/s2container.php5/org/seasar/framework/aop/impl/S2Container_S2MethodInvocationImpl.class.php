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
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
class S2Container_S2MethodInvocationImpl 
                         implements S2Container_S2MethodInvocation
{
    private $interceptorIndex = 0;
    private $interceptors;
    private $method;
    private $methodArgs;
    private $parameters_;

    /**
     * target object
     */
    private $target;

    /**
     * ReflectionClass of target object
     */
    private $targetClass;

    /**
     * 
     */    
    function __construct($target,
                         $targetClass,
                         $method,
                         $methodArgs,
                         $interceptors,
                         $parameters = null)
    {
            
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->method = $method;
        $this->methodArgs = $methodArgs;
        $this->interceptors = $interceptors;
        if (is_array($parameters)) {
            $this->parameters_ = $parameters;
        } else {
            $this->parameters_ = array();
        }
    }

    /**
     * 
     */    
    function getTargetClass()
    {
        return $this->targetClass;
    }

    /**
     * 
     */
    function getParameter($name)
    {
        if (array_key_exists($name,$this->parameters_)) {
             return $this->parameters_[$name];
        }
        return null;
    }
    
    /**
     * 
     */
    function getMethod()
    {
        return $this->method;
    }

    /**
     * 
     */
    function getArguments()
    {
         return $this->methodArgs;
    }

    /**
     * 
     */
    function getStaticPart()
    {
    }

    /**
     * 
     */
    function getThis()
    {
        return $this->target;
    }

    /**
     * 
     */
    function proceed()
    {
        if ($this->interceptorIndex < count($this->interceptors)) {
            return $this->interceptors[$this->interceptorIndex++]->invoke($this);
        } else {
            $method = $this->method->getName();
            if (!is_object($this->target)) {
                throw new S2Container_S2RuntimeException('ESSR1009',
                              array($method,$this->targetClass->getName()));
            }
            return S2Container_MethodUtil::invoke($this->method,
                                    $this->target,$this->methodArgs);
        }
    }
}
?>
