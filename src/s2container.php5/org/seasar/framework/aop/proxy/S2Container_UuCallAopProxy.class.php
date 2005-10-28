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
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
 
class S2Container_UuCallAopProxy {

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
    
    function S2Container_UuCallAopProxy($targetClass,$map,$args,$params) {
        $this->targetClass_=$targetClass;
        $this->methodInterceptorsMap_ = $map;
        $this->parameters_ = $params;

        if(!$this->targetClass_->isInterface() && !$this->targetClass_->isAbstract()){
            $this->target_ = S2Container_ConstructorUtil::newInstance($this->targetClass_,$args);
        }
        
    }
    
    /**
     * @param string Method name
     * @param array Args 
     */
    function __call($name,$args){
        if(array_key_exists($name,$this->methodInterceptorsMap_)){
            $methodInvocation = new S2Container_S2MethodInvocationImpl(
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
        		
        		throw new S2Container_S2RuntimeException('ESSR0043',array($name,$msg));
        	}

            return S2Container_MethodUtil::invoke($this->targetClass_->getMethod($name),
                                       $this->target_,
                                       $args);
        }
    }
}
?>
