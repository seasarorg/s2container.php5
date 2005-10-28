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
abstract class S2Container_ThrowsInterceptor extends S2Container_AbstractInterceptor {

    public static $METHOD_NAME = "handleThrowable";
    private $methods_ = array();

    public abstract function handleThrowable(Exception $t, S2Container_MethodInvocation $invocation);

    public function S2Container_ThrowsInterceptor() {
    }

    private function isHandleThrowable(Method $method) {
    }

    /**
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation) {
        
        try {
            return $invocation->proceed();
        } catch (Exception $t) {
            try{
                $this->handleThrowable($t, $invocation);
            }catch(Exception $t){
                throw $t;
            }
        }
    }

    private function getMethod(Exception $t) {
    }
}
?>