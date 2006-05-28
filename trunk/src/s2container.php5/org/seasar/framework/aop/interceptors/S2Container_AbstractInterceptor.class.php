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
abstract class S2Container_AbstractInterceptor 
    implements S2Container_MethodInterceptor
{

    /**
     * 
     */
    public function createProxy($targetClass)
    {
        $aspect = new S2Container_AspectImpl($this, 
                              new S2Container_PointcutImpl(array(".*")));
        $proxy = S2Container_AopProxyFactory::create(null,
                                                     $targetClass,
                                                     array($aspect),
                                                     array());
        return $proxy;
    }

    /**
     * 
     */
    protected function getTargetClass(S2Container_MethodInvocation $invocation)
    {
        if ($invocation instanceof S2Container_S2MethodInvocation) {
            return $invocation->getTargetClass();
        }
        $thisClass = new ReflectionClass($invocation->getThis());
        $superClass = $thisClass->getParentClass();
        if ($superClass == null) {
            $ifs = S2Container_ClassUtil::getInterfaces($thisClass);
            return $ifs[0];
        } else {
            return $superClass;
        }
    }

    /**
     * 
     */
    protected function getComponentDef(S2Container_MethodInvocation $invocation)
    {
        if ($invocation instanceof S2Container_S2MethodInvocation) {
            return $invocation->
                getParameter(S2Container_ContainerConstants::COMPONENT_DEF_NAME);
        }
        return null;
    }
    
    /**
     * 
     */
    protected function getProxy(S2Container_MethodInvocation $invocation)
    {
        if ($invocation instanceof S2Container_S2MethodInvocation) {
            return $invocation->
                getParameter(S2Container_ContainerConstants::S2AOP_PROXY_NAME);
        }
        return null;
    }    
}
?>
