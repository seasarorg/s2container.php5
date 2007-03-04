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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class S2Container_DelegateInterceptor 
    extends S2Container_AbstractInterceptor
{

    private $target_;
    private $beanDesc_;
    private $methodNameMap_ = array();

    /**
     * 
     */
    public function __construct($target = null)
    {
        if ($target != null) {
            $this->setTarget($target);
        }
    }

    /**
     * 
     */
    public function getTarget()
    {
        return $this->target_;
    }

    /**
     * 
     */
    public function setTarget($target)
    {
        $this->target_ = $target;
        $this->beanDesc_ = 
            S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass($target));
    }
    
    /**
     * 
     */
    public function addMethodNameMap($methodName,$targetMethodName)
    {
        $this->methodNameMap_[$methodName] = $targetMethodName;
    }

    /**
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation)
    {
        if ($this->target_ == null) {
            throw new S2Container_EmptyRuntimeException("target");
        }
        $method = $invocation->getMethod();
        $methodName = $method->getName();
        if (array_key_exists($methodName,$this->methodNameMap_)) {
            $methodName = $this->methodNameMap_[$methodName];
        }
        if ($this->beanDesc_->hasMethod($methodName)) {
            return $this->beanDesc_->invoke($this->target_,
                                            $methodName, 
                                            $invocation->getArguments());
        } else {
            return $invocation->proceed();
        }
    }
}
?>
