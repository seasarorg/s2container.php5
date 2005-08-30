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
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
final class PointcutImpl implements Pointcut {

    private $methodNames_;
    private $patterns_;

    public function PointcutImpl($target=null) {

        if(!is_array($target)){
             
            if ($target == null) {
                throw new EmptyRuntimeException("targetClass");
            }
            if($target instanceof ReflectionClass){
                $this->setMethodNames($this->getMethodNames($target));
            }else{
                $this->setMethodNames($this->getMethodNames(
                                       new ReflectionClass($target)));
            }
        }else{
            if (count($target) == 0) {
                throw new EmptyRuntimeException("methodNames");
            }
            $this->setMethodNames($target);
        }
    }

    public function isApplied($methodName) {
        for ($i = 0;$i < count($this->methodNames_); ++$i) {
        	if(preg_match("/^!(.+)/",$this->methodNames_[$i],$regs)){
                if(!preg_match("/".$regs[1]."/",$methodName)){
               	    return true;
                }
        	}else{
                if(preg_match("/".$this->methodNames_[$i]."/",$methodName)){
               	    return true;
                }
        	}
        }
        return false;
    }
    
    private function setMethodNames($methodNames) {
        $this->methodNames_ = $methodNames;
    }

    private function getMethodNames($targetClass=null) {
        if($targetClass == null){
            return $this->methodNames_;
        }
        $methodNameSet = array();
        
        if($targetClass->isInterface() or $targetClass->isAbstract()){
            $methods = $targetClass->getMethods();
            for ($j = 0; $j < count($methods); $j++) {
                array_push($methodNameSet,$methods[$j]->getName());
            }
        }else{
            $interfaces = $targetClass->getInterfaces();
            for ($i = 0; $i < count($interfaces); $i++) {
                $methods = $interfaces[$i]->getMethods();
                for ($j = 0; $j < count($methods); $j++) {
                    array_push($methodNameSet,$methods[$j]->getName());
                }
            }
        }

        return $methodNameSet;
    }
}
?>
