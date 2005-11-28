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
class S2Container_UuCallAopProxyFactory
{
    /**
     *  
     */
    private function __construct()
    {
    }
    
    /**
     * @param ReflectionClass 
     * @param array Interceptors 
     */
    static function create($targetClass,$map,$args,$params = null)
    {
        $log = S2Container_S2Logger::getLogger('S2Container_UuCallAopProxyFactory');

        if (S2Container_ClassUtil::hasMethod($targetClass,'__call')) {
            $log->info("target class has __call(). ignore aspect.",__METHOD__);
            return S2Container_ConstructorUtil::newInstance($targetClass,$args);
        }

        $concreteClassName = 'S2Container_UuCallAopProxy' . 
             $targetClass->getName() . 'EnhancedByS2AOP';

        if (class_exists($concreteClassName,false)) {
            return new $concreteClassName($targetClass,$map,$args,$params);
        }
        
        if (!$targetClass->isUserDefined()) {
            return new S2Container_UuCallAopProxy($targetClass,$map,$args,$params);
        }

        $classSrc = S2Container_ClassUtil::getClassSource(new 
                        ReflectionClass('S2Container_UuCallAopProxy'));

        $interfaces = S2Container_ClassUtil::getInterfaces($targetClass); 
               
        if (count($interfaces) == 0) {
            return new S2Container_UuCallAopProxy($targetClass,$map,$args,$params);
        }

        $addMethodSrc = array();
        $interfaceNames = array();
        foreach ($interfaces as $interface) {
            $interfaceSrc = S2Container_ClassUtil::getSource($interface);
            $methods = $interface->getMethods();
            $unApplicable = false;
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() == $interface->getName()) {
                    if (S2Container_AopProxy::isApplicableAspect($method)) {
                        array_push($addMethodSrc,
                        S2Container_UuCallAopProxyFactory::getMethodDefinition($method,
                                                           $interfaceSrc));
                    } else {
                        $unApplicable = true;
                        break;
                    }
                }
            }
            if (!$unApplicable) {
                array_push($interfaceNames,$interface->getName());
            } else {
                $log->info("interface [" . 
                    $interface->getName() . 
                    "] is unapplicable. not implemented.",__METHOD__);
            }
        }          

        if (count($interfaceNames) > 0) {
            $implLine = " implements " . implode(',',$interfaceNames) . ' {';
        } else {
            $implLine = ' {';
        }
        
        $srcLine = str_replace('S2Container_UuCallAopProxy',
        $concreteClassName,$classSrc[0]);
        $srcLine = str_replace('{',$implLine,$srcLine);
        for ($i = 1; $i < count($classSrc) - 1; $i++) {
            $srcLine .= str_replace('S2Container_UuCallAopProxy',
            $concreteClassName,$classSrc[$i]);
        }
        
        foreach ($addMethodSrc as $methodSrc) {
            $srcLine .= $methodSrc . "\n";
        }

        $srcLine .= "}\n";
        eval($srcLine);
        return new $concreteClassName($targetClass,$map,$args,$params);
    }
    
    /**
     * 
     */
    private static function getMethodDefinition($refMethod,$interfaceSrc)
    {
        $def = S2Container_MethodUtil::getSource($refMethod,$interfaceSrc);        
        $defLine = trim(implode(' ',$def));
        $defLine = preg_replace("/\;$/","",$defLine);
        $defLine = preg_replace("/abstract\s/","",$defLine);
        $defLine .= " {";
        
        if (preg_match("/\((.*)\)/",$defLine,$regs)) {
            $argLine = $regs[1];
        }
                
        $argsTmp = split('[ ,]',$argLine);
        $args = array();
        foreach ($argsTmp as $item) {
            if (preg_match('/^\$/',$item)) {
                array_push($args,$item);
            }
            if (preg_match('/^\&(.+)/',$item,$regs)) {
                array_push($args,$regs[1]);
            }
        }
        $argLine = implode(',',$args);
        $defLine .= ' return $this->__call(\'' . $refMethod->getName() .
                    '\',array(' . $argLine . ')); }';
        
        return $defLine;
    }
}
?>
