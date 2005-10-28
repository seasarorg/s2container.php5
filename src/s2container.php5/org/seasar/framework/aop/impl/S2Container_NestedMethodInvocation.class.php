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
class S2Container_NestedMethodInvocation implements S2Container_S2MethodInvocation {
    private $parent;
    private $interceptors;
    private $interceptorsIndex = 0;

    public function S2Container_NestedMethodInvocation(S2Container_S2MethodInvocation $parent,
            $interceptors) {
        $this->parent = $parent;
        $this->interceptors = $interceptors;
    }

    public function proceed() {
        if ($this->interceptorsIndex < count($this->interceptors)) {
            return $this->invokeInterceptor(
                $this->interceptors[$this->interceptorsIndex++]);
        }
        return $this->parent->proceed();
    }

    public function getThis() {
        return $this->parent->getThis();
    }

    public function getArguments() {
        return $this->parent->getArguments();
    }

    public function getMethod() {
        return $this->parent->getMethod();
    }

    public function getStaticPart() {
        return $this->parent->getStaticPart();
    }

    public function getTargetClass() {
        return $this->parent->getTargetClass();
    }

    public function getParameter($name) {
        return $this->parent->getParameter($name);
    }
    
    private function invokeInterceptor(S2Container_MethodInterceptor $interceptor){
    	return $interceptor->invoke($this);
    }
}
?>