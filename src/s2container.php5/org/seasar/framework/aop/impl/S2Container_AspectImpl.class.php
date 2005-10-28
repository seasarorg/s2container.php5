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
class S2Container_AspectImpl implements S2Container_Aspect {

    private $methodInterceptor_;
    private $pointcut_;

    public function S2Container_AspectImpl(S2Container_MethodInterceptor $methodInterceptor,S2Container_Pointcut $pointcut) {
        $this->methodInterceptor_ = $methodInterceptor;
        $this->pointcut_ = $pointcut;
    }

    /**
     * @see S2Container_Aspect::getMethodInterceptor()
     */
    public function getMethodInterceptor() {
        return $this->methodInterceptor_;
    }

    /**
     * @see S2Container_Aspect::getPointcut()
     */
    public function getPointcut() {
        return $this->pointcut_;
    }

    /**
     * @see S2Container_Aspect::setPointcut()
     */
    public function setPointcut(S2Container_Pointcut $pointcut) {
        $this->pointcut_ = $pointcut;
    }
}
?>
