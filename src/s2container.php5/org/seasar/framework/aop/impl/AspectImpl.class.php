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
// $Id: AspectImpl.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
class AspectImpl implements Aspect {

    private $methodInterceptor_;
    private $pointcut_;

    public function AspectImpl(MethodInterceptor $methodInterceptor,Pointcut $pointcut) {
        $this->methodInterceptor_ = $methodInterceptor;
        $this->pointcut_ = $pointcut;
    }

    /**
     * @see Aspect::getMethodInterceptor()
     */
    public function getMethodInterceptor() {
        return $this->methodInterceptor_;
    }

    /**
     * @see Aspect::getPointcut()
     */
    public function getPointcut() {
        return $this->pointcut_;
    }

    /**
     * @see Aspect::setPointcut()
     */
    public function setPointcut(Pointcut $pointcut) {
        $this->pointcut_ = $pointcut;
    }
}
?>
