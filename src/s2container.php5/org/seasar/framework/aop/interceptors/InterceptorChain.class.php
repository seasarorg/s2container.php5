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
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class InterceptorChain extends AbstractInterceptor {
    
    private $interceptors = array();

    public function add(MethodInterceptor $interceptor) {
        array_push($this->interceptors,$interceptor);
    }

    public function invoke(MethodInvocation $invocation){
        $nestInvocation = new NestedMethodInvocation($invocation,
                $this->interceptors);
        return $nestInvocation->proceed();
    }
}
?>
