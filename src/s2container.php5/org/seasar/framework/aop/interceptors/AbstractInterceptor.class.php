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
abstract class AbstractInterceptor implements MethodInterceptor {

    public function createProxy($proxyClass) {
        $aspect = new AspectImpl($this, new PointcutImpl(array(".*")));
        $proxy = new AopProxy($proxyClass, array($aspect));
        return $proxy->create();
    }

    protected function getTargetClass(MethodInvocation $invocation) {
        if ($invocation instanceof S2MethodInvocation) {
            return $invocation->getTargetClass();
        }
        $thisClass = new ReflectionClass($invocation->getThis());
        $superClass = $thisClass->getParentClass();
        if ($superClass == null) {
            $ifs = $thisClass->getInterfaces();
            return $ifs[0];
        } else {
            return $superClass;
        }
    }

    protected function getComponentDef(MethodInvocation $invocation) {
        if ($invocation instanceof S2MethodInvocation) {
            return $invocation->getParameter(ContainerConstants::COMPONENT_DEF_NAME);
        }
        return null;
    }
}
?>
