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
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
final class S2Container_PointcutImpl implements S2Container_Pointcut
{
    private $methodNames_;
    private $patterns_;

    /**
     * 
     */
    public function __construct($target = null)
    {
        if ($target == null) {
            throw new S2Container_EmptyRuntimeException("targetClass");
        }

        if (is_array($target)) {
            if (count($target) == 0) {
                throw new S2Container_EmptyRuntimeException("methodNames");
            }
            $this->_setMethodNames($target);
        } else {
            if ($target instanceof ReflectionClass) {
                $this->_setMethodNames($this->_getMethodNames($target));
            } else {
                $this->_setMethodNames($this->_getMethodNames(new ReflectionClass($target)));
            }
        }
    }

    /**
     * 
     */
    public function isApplied($methodName)
    {
        $o = count($this->methodNames_);
        for ($i = 0; $i < $o; ++$i) {
            if (preg_match("/" . $this->methodNames_[$i] . "/",$methodName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     */    
    private function _setMethodNames($methodNames)
    {
        $this->methodNames_ = $methodNames;
    }

    /**
     * 
     */
    private function _getMethodNames($targetClass = null)
    {
        if ($targetClass == null) {
            return $this->methodNames_;
        }
        $methodNameSet = array();
        
        if ($targetClass->isInterface() or $targetClass->isAbstract()) {
            $methods = $targetClass->getMethods();
            $o = count($methods);
            for ($j = 0; $j < $o; $j++) {
                array_push($methodNameSet,$methods[$j]->getName());
            }
        } else {
            $interfaces = $targetClass->getInterfaces();
            $o = count($interfaces);
            for ($i = 0; $i < $o; $i++) {
                $methods = $interfaces[$i]->getMethods();
                $p = count($methods);
                for ($j = 0; $j < $p; $j++) {
                    array_push($methodNameSet,$methods[$j]->getName());
                }
            }
        }
        return $methodNameSet;
    }
}
?>
