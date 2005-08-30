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
// $Id: NestedMethodInvocation.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
class NestedMethodInvocation implements S2MethodInvocation {
    private $parent;
    private $interceptors;
    private $interceptorsIndex = 0;

    public function NestedMethodInvocation(S2MethodInvocation $parent,
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
    
    private function invokeInterceptor(MethodInterceptor $interceptor){
    	return $interceptor->invoke($this);
    }
}
?>