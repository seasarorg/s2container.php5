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
// $Id: MockInterceptor.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class MockInterceptor extends AbstractInterceptor {

    private $returnValueMap_ = array();

    private $throwableMap_ = array();

    private $invokedMap_ = array();

    private $argsMap_ = array();

    public function MockInterceptor($methodName=null,$value=null) {
        if($methodName != null and $value != null){
            $this->setReturnValue($methodName,$value);
        }
    }
    public function setReturnValue($methodName,$returnValue) {
        $this->returnValueMap_[$methodName] = $returnValue;
    }
    public function setThrowable($methodName,$throwable) {
        $this->throwableMap_[$methodName] = $throwable;
    }

    public function isInvoked($methodName) {
        return array_key_exists($methodName,$this->invokedMap_);
    }

    public function getArgs($methodName) {
    	if(array_key_exists($methodName,$this->argsMap_)){
            return $this->argsMap_[$methodName];
    	}
  	    return null;	
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation){
        $methodName = $invocation->getMethod()->getName(); 
        $this->invokedMap_[$methodName] = true;

        if (array_key_exists($methodName,$this->throwableMap_)) {
            throw $this->throwableMap_[$methodName];
        } else if (array_key_exists(null,$this->throwableMap_)) {
            throw $this->throwableMap_[null];
        } else if (array_key_exists($methodName,$this->returnValueMap_)) {
            return $this->returnValueMap_[$methodName];
        } else {
            return $this->returnValueMap_[null];
        }
    }
}
?>