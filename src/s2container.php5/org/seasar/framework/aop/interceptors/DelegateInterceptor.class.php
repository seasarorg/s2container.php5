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
// $Id: DelegateInterceptor.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class DelegateInterceptor extends AbstractInterceptor {

    private $target_;
    private $beanDesc_;
    private $methodNameMap_ = array();

    public function DelegateInterceptor($target=null) {
        if($target != null){
            $this->setTarget($target);
        }
    }
    
    public function getTarget() {
        return $this->target_;
    }

    public function setTarget($target) {
        $this->target_ = $target;
        $this->beanDesc_ = BeanDescFactory::getBeanDesc(new ReflectionClass($target));
    }
    
    public function addMethodNameMap($methodName,$targetMethodName) {
        $this->methodNameMap_[$methodName] = $targetMethodName;
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation) {
        if ($this->target_ == null) {
            throw new EmptyRuntimeException("target");
        }
        $method = $invocation->getMethod();
        $methodName = $method->getName();
        if (array_key_exists($methodName,$this->methodNameMap_)){
            $methodName = $this->methodNameMap_[$methodName];
        }
        if ($this->beanDesc_->hasMethod($methodName)) {
            return $this->beanDesc_->invoke($this->target_, $methodName, $invocation->getArguments());
        }else{
            return $invocation->proceed();
        }
    }
}
?>
