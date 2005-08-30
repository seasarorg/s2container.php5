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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class AspectDefImpl extends ArgDefImpl implements AspectDef {

    private $pointcut_;

    public function AspectDefImpl($arg1=null,$arg2=null) {

        parent::__construct();
        
        if($arg1 instanceof Pointcut){
            $this->pointcut_ = $arg1;
        }
        if($arg2 instanceof Pointcut){
            $this->pointcut_ = $arg2;
        }
        if($arg1 instanceof MethodInterceptor){
            $this->setValue($arg1);
        }
        if($arg2 instanceof MethodInterceptor){
            $this->setValue($arg2);
        }
    }

    /**
     * @see AspectDef::getAspect()
     */    
    public function getAspect() {
        $interceptor = $this->getValue();
        return new AspectImpl($interceptor, $this->pointcut_);
    }
}