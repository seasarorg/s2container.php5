<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
class S2Container_AopProxyGenerator
{
    const CLASS_NAME_PREFIX = '';
    const CLASS_NAME_POSTFIX = 'EnhancedByS2AOP';

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
    public static function generate($targetClass)
    {
        $concreteClassName = 
            self::getConcreteClassName($targetClass->getName());

        if (class_exists($concreteClassName, false)) {
            return $concreteClassName;
        }

        $support = S2Container_CacheSupportFactory::create();
        if (!$support->isAopProxyCaching($targetClass->getFileName() . $concreteClassName)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                debug("set caching off.",__METHOD__);
            $srcLine = self::generateInternal($concreteClassName, $targetClass);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }

        if ($srcLine = $support->loadAopProxyCache($targetClass->getFileName() . $concreteClassName)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                    debug("cached aop proxy found.",__METHOD__);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }
        else {
            S2Container_S2Logger::getLogger(__CLASS__)->
                debug("create aop proxy and cache it.",__METHOD__);
            $srcLine = self::generateInternal($concreteClassName, $targetClass);
            $support->saveAopProxyCache($srcLine, $targetClass->getFileName() . $concreteClassName);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }
    }

    private static function evalInternal($srcLine) {
        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') and S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->
                debug("[ $srcLine ]",__METHOD__);
        }
        eval($srcLine);
    }

    /**
     * @param ReflectionClass 
     * @param array Interceptors 
     */
    private static function generateInternal($concreteClassName, $targetClass)
    {
        $interfaces = S2Container_ClassUtil::getInterfaces($targetClass); 
        $classSrc = 
          S2Container_ClassUtil::getClassSource(new 
                         ReflectionClass('S2Container_DefaultAopProxy'));
        $addMethodSrc = array();
        $interfaceNames = array();
        $repeatInterfaces = array();
        foreach ($interfaces as $interface) {
            $interfaceSrc = S2Container_ClassUtil::getSource($interface);
            $methods = $interface->getMethods();
            $hasUnApplicableMethod = false;
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() == $interface->getName()) {
                    if (S2Container_AopProxyFactory::isApplicableAspect($method)) {
                        $addMethodSrc[] =
                             S2Container_AopProxyGenerator::getMethodDefinition($method,
                             $interfaceSrc);
                    } else {
                        $hasUnApplicableMethod = true;
                        break;
                    }
                } else {
                    $repeatInterfaces[] = $method->getDeclaringClass()->getName();
                }
            }
            if (!$hasUnApplicableMethod) {
                $interfaceNames[] = $interface->getName();
            }
        }

        $interfaceNames = array_diff($interfaceNames, $repeatInterfaces);
        if (count($interfaceNames) > 0) {
            $implLine = ' implements ' . implode(',',$interfaceNames) . ' {';
        } else {
            $implLine = ' {';
        }
        
        $srcLine = str_replace('S2Container_DefaultAopProxy',
                                $concreteClassName,$classSrc[0]);
        $srcLine = str_replace('{',$implLine,$srcLine);
        $o = count($classSrc) - 1;
        for ($i = 1; $i < $o; $i++) {
            $srcLine .= str_replace('S2Container_DefaultAopProxy',
                        $concreteClassName,$classSrc[$i]);
        }
        
        foreach ($addMethodSrc as $methodSrc) {
            $srcLine .= '    ' . $methodSrc . PHP_EOL;
        }

        $srcLine .= '}' . PHP_EOL;
        return $srcLine;
    }

    /**
     * @param string target class name
     */
    public static function getConcreteClassName($targetClassName)
    {
        return self::CLASS_NAME_PREFIX .
               $targetClassName . 
               self::CLASS_NAME_POSTFIX;
    }
    
    /**
     * @param ReflectionMethod
     * @param string
     */
    public static function getMethodDefinition($refMethod,$interfaceSrc)
    {
        $srcLines = S2Container_MethodUtil::getSource($refMethod,$interfaceSrc);        
        $srcLine = implode(' ',$srcLines);
        $defLine = 'public function ';
        
        if (preg_match("/\s([^\s]+?)\s*?\((.*)\)\s*?;/",$srcLine,$regs)) {
            if($refMethod->getName() != $regs[1] or 
               preg_match("/^[^\"']*function/",$regs[2])){
                $msg = "cannot get method definition [ $srcLine ]";
                throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
            }

            $defLine .= $regs[1] . '(' . $regs[2] . '){';
            $argLine = $regs[2];
        }else{
            $msg = "cannot get args [ $srcLine ]";
            throw new S2Container_S2RuntimeException('ESSR0017',array($msg));   
        }

        $argLine = preg_replace('/\".*?\"/','',$argLine);
        $argLine = preg_replace('/\'.*?\'/','',$argLine);
        $argsTmp = preg_split('/[ ,\&=]/',$argLine);
        $args = array();
        foreach ($argsTmp as $item) {
            if (preg_match('/^\$/',trim($item))) {
                $args[] = $item;
            }
        }
        
        $defLine .= ' return $this->__call(\'' . $refMethod->getName() .
                    '\',array(' . implode(',',$args) . ')); }';
        
        return $defLine;
    }
}
?>
