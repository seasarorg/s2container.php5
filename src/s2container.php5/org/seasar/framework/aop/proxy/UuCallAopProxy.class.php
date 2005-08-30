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
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class UuCallAopProxy {

    private $parameters_;
    private $methodInterceptorsMap_;
    
    /**
     * Object
     */
    private $target_ = null;
    
    /**
     * ReflectionClass 
     */
    private $targetClass_;
    
    function UuCallAopProxy($targetClass,$map,$args,$params) {
        $this->targetClass_=$targetClass;
        $this->methodInterceptorsMap_ = $map;
        $this->parameters_ = $params;

        if(!$this->targetClass_->isInterface() && !$this->targetClass_->isAbstract()){
            $this->target_ = ConstructorUtil::newInstance($this->targetClass_,$args);
        }
        
    }
    
    /**
     * @param string Method name
     * @param array Args 
     */
    function __call($name,$args){
        if(array_key_exists($name,$this->methodInterceptorsMap_)){
            $methodInvocation = new S2MethodInvocationImpl(
                                    $this->target_,
                                    $this->targetClass_,
                                    $this->targetClass_->getMethod($name),
                                    $args,
                                    $this->methodInterceptorsMap_[$name],
                                    $this->parameters_);
            return $methodInvocation->proceed();
        }else{
        	if(!is_object($this->target_)){
        		if($this->targetClass_->isInterface()){
        			$msg = "target class [{$this->targetClass_->getName()}] is interface. ";
        		}else{
        			$msg = "target class [{$this->targetClass_->getName()}] ";
        		}
        		
        		throw new S2RuntimeException('ESSR0043',array($name,$msg));
        	}

            return MethodUtil::invoke($this->targetClass_->getMethod($name),
                                       $this->target_,
                                       $args);
        }
    }
}
?>
