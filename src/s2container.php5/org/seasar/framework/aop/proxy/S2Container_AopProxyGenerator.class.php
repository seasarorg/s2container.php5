<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
    public static function generate($target,$targetClass,$params = null)
    {
        $concreteClassName = 
            self::getConcreteClassName($targetClass->getName());

        if (class_exists($concreteClassName,false)) {
            return $concreteClassName;
        }

        if (S2Container_FileCacheUtil::isAopCache()) {
            if (S2Container_FileCacheUtil::loadAopCache($concreteClassName,
               $targetClass->getFileName())) {
                return $concreteClassName;
            }
        }

        $interfaces = S2Container_ClassUtil::getInterfaces($targetClass); 
        $classSrc = 
          S2Container_ClassUtil::getClassSource(new 
                         ReflectionClass('S2Container_DefaultAopProxy'));
        $addMethodSrc = array();
        $interfaceNames = array();
        foreach ($interfaces as $interface) {
            $interfaceSrc = S2Container_ClassUtil::getSource($interface);
            $methods = $interface->getMethods();
            $unApplicable = false;
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() == $interface->getName()) {
                    if (S2Container_AopProxyFactory::isApplicableAspect($method)) {
                        $addMethodSrc[] =
                             S2Container_AopProxyGenerator::getMethodDefinition($method,
                             $interfaceSrc);
                    } else {
                        $unApplicable = true;
                        break;
                    }
                }
            }
            if (!$unApplicable) {
                $interfaceNames[] = $interface->getName();
            }
        }          

        if (count($interfaceNames) > 0) {
            $implLine = " implements " . implode(',',$interfaceNames) . ' {';
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
            $srcLine .= "    " . $methodSrc . "\n";
        }

        $srcLine .= "}\n";

        if (S2Container_FileCacheUtil::isAopCache()) {
            S2Container_FileCacheUtil::saveAopCache($concreteClassName,$srcLine);
        }

        S2Container_S2Logger::getLogger(__CLASS__)->
            debugEval("[ $srcLine ]",__METHOD__);
        eval($srcLine);
        return $concreteClassName;
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

            $defLine .= $regs[1] . "(" . $regs[2] . "){";
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
