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
// $Id:$
/**
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class S2Container_AopConcreteClassGenerator
{
    const CLASS_NAME_POSTFIX = '_EnhancedByS2AOP';

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
    public static function generate(ReflectionClass $targetClass, array $applicableMethods, array $interTypes)
    {
        $concreteClassName = $targetClass->getName() . self::CLASS_NAME_POSTFIX;

        if (class_exists($concreteClassName, false)) {
            return $concreteClassName;
        }

        $support = S2Container_CacheSupportFactory::create();
        if (!$support->isAopProxyCaching($targetClass->getFileName() . $concreteClassName)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                debug("set caching off.",__METHOD__);
            $srcLine = self::generateInternal($concreteClassName, $targetClass, $applicableMethods, $interTypes);
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
            $srcLine = self::generateInternal($concreteClassName, $targetClass, $applicableMethods, $interTypes);
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
    public static function generateInternal($concreteClassName, ReflectionClass $targetClass, array $applicableMethods, array $interTypes)
    {
        self::validateTargetClass($targetClass);
        $srcLine = 'class ' . $concreteClassName . ' ';
        if ($targetClass->isInterface()) {
            $srcLine .= 'implements ' . $targetClass->getName() . ' { ';
        } else {
            $srcLine .= 'extends ' . $targetClass->getName() . ' { ';
        }
        $srcLine .= PHP_EOL;
        $srcLine .= '    public $clazz_EnhancedByS2AOP = null;
    public $concreteClazz_EnhancedByS2AOP = null;
    public $methodInterceptorsMap_EnhancedByS2AOP = null;
    public $parameters_EnhancedByS2AOP = null;' . PHP_EOL;
        $classSrc = S2Container_ClassUtil::getSource($targetClass);
        $abstractMethods = array();
        foreach ($applicableMethods as $methodName) {
            $refMethod = $targetClass->getMethod($methodName);
            if ($refMethod->isAbstract()) {
                $abstractMethods[] = $refMethod->getName();
                $methodDef = self::getAbstractMethodDefinition($refMethod, $classSrc);
            } else {
                $methodDef = self::getMethodDefinition($refMethod, $classSrc);
            }
            self::validateMethodName($refMethod, $methodDef);
            $srcLine .= self::getMethodSrc($targetClass, $refMethod, $methodDef);
            $srcLine .= PHP_EOL;
        }
        $srcLine .= self::addInvokeParentMethodSrc($abstractMethods);
        $srcLine .= PHP_EOL;
        $srcLine .= self::addInvokeProceedMethodSrc();
        $srcLine .= self::addInterTypeSrc($targetClass, $interTypes);
        $srcLine .= '}' . PHP_EOL;
        return $srcLine;
    }

    public static function getAbstractMethodDefinition(ReflectionMethod $refMethod, $interfaceSrc)
    {
        $srcLines = S2Container_MethodUtil::getSource($refMethod, $interfaceSrc);
        $srcLine = implode(' ', $srcLines);
        return $srcLine;
    }

    /**
     * @param ReflectionMethod
     * @param string
     */
    public static function getMethodDefinition(ReflectionMethod $refMethod,$classSrc)
    {
        $srcLines = S2Container_MethodUtil::getSource($refMethod,$classSrc);
        $srcLine = implode(' ', $srcLines);
        $matches = array();
        if (preg_match('/^(.+?\))\s*{/', $srcLine, $matches)) {
            $srcLine = $matches[1] . ';';
        } else {
            $msg = "cannot get method definition. [$srcLine]";
            throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
        }
        return $srcLine;
    }

    public static function validateMethodName(ReflectionMethod $refMethod, $methodDef)
    {
        $matches = array();
        if (preg_match('/([^\s]+?)\s*\(/', $methodDef, $matches)) {
            if ($matches[1] !== $refMethod->getName()) {
                $msg = "cannot get method definition [{$matches[1]} supposed to be {$refMethod->getName()}]. [$methodDef]";
                throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
            }
        } else {
            $msg = "cannot get method name from definition. [$methodDef]";
            throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
        }
    }


    public static function addInvokeParentMethodSrc(array $abstractMethods)
    {
        $methods = implode('\',\'', $abstractMethods);
        $methodContent = '    private $abstractMethods_EnhancedByS2AOP = array(\'' . $methods . '\');
    private function __invokeParentMethod_EnhancedByS2AOP() {
        $args = func_get_args();
        $methodName = array_pop($args);
        if (in_array($methodName, $this->abstractMethods_EnhancedByS2AOP)) {
            $msg = \'cannot invoke abstract method. [\' . $methodName . \']\';
            throw new S2Container_S2RuntimeException(\'ESSR0017\',array($msg));
        }
        return call_user_func_array(array($this, \'parent::\' . $methodName), $args);
    }';
        return $methodContent . PHP_EOL;
        /*
        $argNum = count($args);
        $argLine = array();
        for ($i=0; $i<$argNum; $i++) { $argLine[] = \'$args[\' . $i . \']\';}
        $argLine = implode(\',\', $argLine);
        $cmd = \'return parent::\' . $methodName . \'(\' . $argLine . \');\';
        return eval($cmd);
        */
    }

    public static function addInvokeProceedMethodSrc()
    {
        $methodContent = '    private function __invokeMethodInvocationProceed_EnhancedByS2AOP() {
        $args = func_get_args();
        $methodName = array_pop($args);
        $methodInvocation = new S2Container_S2MethodInvocationImpl($this, $this->clazz_EnhancedByS2AOP, $this->concreteClazz_EnhancedByS2AOP, $this->concreteClazz_EnhancedByS2AOP->getMethod($methodName), $this->concreteClazz_EnhancedByS2AOP->getMethod($methodName . \'_EnhancedByS2AOP\'), $args, $this->methodInterceptorsMap_EnhancedByS2AOP[$methodName], $this->parameters_EnhancedByS2AOP);
        return $methodInvocation->proceed();
    }';
        return $methodContent . PHP_EOL;
    }

    /**
     * @param ReflectionMethod
     * @param string
     */
    public static function getMethodSrc(ReflectionClass $targetClass, ReflectionMethod $refMethod, $methodDef)
    {
        //$methodDef = preg_replace("/protected\s/i", 'public ', $methodDef, 1);
        $methodDef = preg_replace("/abstract\s/i", '', $methodDef, 1);
        $methodDef = preg_replace('/;$/', ' {', $methodDef);
        $params = $refMethod->getParameters();
        $args = array();
        foreach ($params as $param) {
            $args[] = '$' . $param->getName();
        }
        $args[] = '\'' . $refMethod->getName() . '\'';
        $args = implode(', ', $args);
        $parentMethodName = $refMethod->getName() . self::CLASS_NAME_POSTFIX;
        if ($targetClass->hasMethod($parentMethodName)) {
            $msg = "target class has [{$targetClass->getName()}::$parentMethodName] method. cannot aspect.";
            throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
        }

        $parentMethodDef  = preg_replace("/{$refMethod->getName()}/", $parentMethodName, $methodDef, 1) . PHP_EOL;
        $parentMethodContent ='        return $this->__invokeParentMethod_EnhancedByS2AOP(' . $args . ');' . PHP_EOL;
        $methodContent = '    ' . $parentMethodDef . $parentMethodContent . '    }' . PHP_EOL;

        $methodContent .= '    ' . $methodDef;
        $methodContent .= '
        if (array_key_exists(\'@@METHOD_NAME@@\', $this->methodInterceptorsMap_EnhancedByS2AOP)) {
            return $this->__invokeMethodInvocationProceed_EnhancedByS2AOP(' . $args . ');
        }
        return $this->__invokeParentMethod_EnhancedByS2AOP(' . $args . ');
    }' . PHP_EOL;
        $methodContent  = preg_replace('/@@METHOD_NAME@@/', $refMethod->getName(), $methodContent);
        return $methodContent;
    }

    private static function addInterTypeSrc(ReflectionClass $targetClass, array $interTypes) {
        return '';
    }

    private static function validateTargetClass(ReflectionClass $targetClass) {
        if ($targetClass->hasProperty('clazz_EnhancedByS2AOP') or
            $targetClass->hasProperty('concreteClazz_EnhancedByS2AOP') or
            $targetClass->hasProperty('methodInterceptorsMap_EnhancedByS2AOP') or
            $targetClass->hasProperty('parameters_EnhancedByS2AOP') or
            $targetClass->hasMethod('__invokeParentMethod_EnhancedByS2AOP') or
            $targetClass->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP')) {
            $msg = "target class has property(clazz_EnhancedByS2AOP,concreteClazz_EnhancedByS2AOP,methodInterceptorsMap_EnhancedByS2AOP,parameters_EnhancedByS2AOP) or method(__invokeParentMethod_EnhancedByS2AOP,__invokeMethodInvocationProceed_EnhancedByS2AOP), cannot aspect.";
            throw new S2Container_S2RuntimeException('ESSR0017',array($msg));
        }
    }
}
?>
