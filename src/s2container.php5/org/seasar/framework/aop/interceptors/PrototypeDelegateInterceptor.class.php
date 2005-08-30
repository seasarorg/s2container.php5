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
class PrototypeDelegateInterceptor extends AbstractInterceptor {
    private $container;
    private $targetName;
    private $beanDesc;
    private $methodNameMap = array();

    public function PrototypeDelegateInterceptor(S2Container $container) {
        $this->container = $container;
    }

    public function getTargetName() {
        return $this->targetName;
    }

    public function setTargetName($targetName) {
        $this->targetName = $targetName;
    }

    public function addMethodNameMap($methodName,$targetMethodName) {
        $this->methodNameMap[$methodName] = $targetMethodName;
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation) {
        if ($this->targetName == null) {
            throw new EmptyRuntimeException("targetName");
        }

        $method = $invocation->getMethod();
        $methodName = $method->getName();

        if (array_key_exists($methodName,$this->methodNameMap)) {
            $methodName = $this->methodNameMap[$methodName];
        }
        $target = $this->container->getComponent($this->targetName);
        if ($this->beanDesc == null) {
            $this->beanDesc = BeanDescFactory::getBeanDesc(new ReflectionClass($target));
        }

        if (!$this->beanDesc->hasMethod($methodName)) {
            throw new MethodNotFoundRuntimeException($this->getTargetClass($invocation), $methodName,
                    $invocation->getArguments());
        }

        return $this->beanDesc->invoke($target, $methodName, $invocation->getArguments());
    }
}
?>
