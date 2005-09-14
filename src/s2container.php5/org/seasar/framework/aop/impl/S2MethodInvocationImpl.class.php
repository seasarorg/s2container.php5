<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
class S2MethodInvocationImpl implements S2MethodInvocation{
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
    
    function S2MethodInvocationImpl(
        $target,
        $targetClass,
        $method,
        $methodArgs,
        $interceptors,
        $parameters=null) {
            
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->method = $method;
        $this->methodArgs = $methodArgs;
        $this->interceptors = $interceptors;
        if(is_array($parameters)){
            $this->parameters_ = $parameters;
        }else{
            $this->parameters_ = array();
        }
    }
    
    function getTargetClass() {
    	return $this->targetClass;
    }

    function getParameter($name) {
        if(array_key_exists($name,$this->parameters_)){
        	return $this->parameters_[$name];
        }
        return null;
    }
    
    function getMethod() {
        return $this->method;
    }

    function getArguments(){
         return $this->methodArgs;
    }

    function getStaticPart(){
    }

    function getThis(){
        return $this->target;
    }

    function proceed(){
        if($this->interceptorIndex < count($this->interceptors)){
            return $this->interceptors[$this->interceptorIndex++]->invoke($this);
        }else{
            $method = $this->method->getName();
            return MethodUtil::invoke($this->method,$this->target,$this->methodArgs);
        }
    }
}
?>
