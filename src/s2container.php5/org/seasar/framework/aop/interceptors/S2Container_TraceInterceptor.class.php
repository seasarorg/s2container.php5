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
class S2Container_TraceInterceptor extends S2Container_AbstractInterceptor {

    /**
     * @see S2Container_MethodInterceptor::invoke()
     */
    public function invoke(S2Container_MethodInvocation $invocation){
        $buf = "";
        $buf .= $this->getTargetClass($invocation)->getName();
        $buf .= "#";
        $buf .= $invocation->getMethod()->getName();
        $buf .= "(";
        $args = $invocation->getArguments(); 
        if ($args != null && count($args) > 0) { 
            $buf .= implode(',',$args);
        }
        $buf .= ")";
        $ret = null;
        $cause = null;
        print "BEGIN " . $buf ."\n";
        try {
            $ret = $invocation->proceed();
            $buf .= " : ";
            $buf .= $ret;
        } catch (Exception $t) {
            $buf .= " Exception:";
            $buf .= $t;
            $cause = $t;
        }
        print "END   " . $buf ."\n";
        if ($cause == null) {
            return $ret;
        } else {
            throw $cause;
        }
    }
}
?>