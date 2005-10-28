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
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class S2Container_MockInterceptor extends S2Container_AbstractInterceptor {

    private $returnValueMap_ = array();

    private $throwableMap_ = array();

    private $invokedMap_ = array();

    private $argsMap_ = array();

    public function S2Container_MockInterceptor($methodName=null,$value=null) {
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
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation){
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