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
abstract class ThrowsInterceptor extends AbstractInterceptor {

    public static $METHOD_NAME = "handleThrowable";
    private $methods_ = array();

    public abstract function handleThrowable(Exception $t, MethodInvocation $invocation);

    public function ThrowsInterceptor() {
    }

    private function isHandleThrowable(Method $method) {
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation) {
        
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
