<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
class S2Container_PrototypeDelegateInterceptor 
    extends S2Container_AbstractInterceptor
{
    private $container;
    private $targetName;
    private $beanDesc;
    private $methodNameMap = array();

    /**
     * @param S2Container container
     */
    public function __construct(S2Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return string target name
     */
    public function getTargetName()
    {
        return $this->targetName;
    }

    /**
     * @param string target name
     */
    public function setTargetName($targetName)
    {
        $this->targetName = $targetName;
    }

    /**
     * @param string method name
     * @param string target method name 
     */
    public function addMethodNameMap($methodName,$targetMethodName)
    {
        $this->methodNameMap[$methodName] = $targetMethodName;
    }

    /**
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation)
    {
        if ($this->targetName == null) {
            throw new S2Container_EmptyRuntimeException("targetName");
        }

        $method = $invocation->getMethod();
        $methodName = $method->getName();

        if (array_key_exists($methodName,$this->methodNameMap)) {
            $methodName = $this->methodNameMap[$methodName];
        }
        $target = $this->container->getComponent($this->targetName);
        if ($this->beanDesc == null) {
            $this->beanDesc = 
                S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass($target));
        }

        if (!$this->beanDesc->hasMethod($methodName)) {
            throw new S2Container_MethodNotFoundRuntimeException($this->
                                               getTargetClass($invocation),
                                               $methodName,
                                               $invocation->getArguments());
        }
        return $this->beanDesc->invoke($target, 
                                       $methodName, 
                                       $invocation->getArguments());
    }
}
?>
