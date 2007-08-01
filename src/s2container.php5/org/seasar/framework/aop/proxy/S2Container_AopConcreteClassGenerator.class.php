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
    public static function generate(ReflectionClass $targetClass, array $applicableMethods)
    {
        $concreteClassName = $targetClass->getName() . self::CLASS_NAME_POSTFIX;

        if (class_exists($concreteClassName, false)) {
            return $concreteClassName;
        }

        $support = S2Container_CacheSupportFactory::create();
        if (!$support->isAopProxyCaching($targetClass->getFileName() . $concreteClassName)) {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("set caching off.",__METHOD__);
            $srcLine = self::generateInternal($concreteClassName, $targetClass, $applicableMethods);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }

        if ($srcLine = $support->loadAopProxyCache($targetClass->getFileName() . $concreteClassName)) {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("cached aop proxy found.",__METHOD__);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }
        else {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("create aop proxy and cache it.",__METHOD__);
            $srcLine = self::generateInternal($concreteClassName, $targetClass, $applicableMethods);
            $support->saveAopProxyCache($srcLine, $targetClass->getFileName() . $concreteClassName);
            self::evalInternal($srcLine);
            return $concreteClassName;
        }
    }

    private static function evalInternal($srcLine) {
        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') and S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->debug("[ $srcLine ]",__METHOD__);
        }
        eval($srcLine);
    }

    public static function getMethodDefSrc(ReflectionMethod $method) {
        $src = 'public function ' . $method->getName() . '(';
        $params = $method->getParameters();
        $paramSrcs = array();
        foreach($params as $param) {
            $paramSrc = '';
            if ($param->isArray()) {
                $paramSrc .= 'array ';
            } else if ($param->getClass() !== null) {
                $paramSrc .= $param->getClass()->getName() . ' ';
            }
            if ($param->isPassedByReference()) {
                $paramSrc .= '&';
            }
            $paramSrc .= '$' . $param->getName();

            if ($param->isDefaultValueAvailable()) {
                $valSrc = '';
                $defaultValue = $param->getDefaultValue();
                if (is_string($defaultValue)) {
                    $valSrc .= "'$defaultValue'";
                } else if (is_array($defaultValue)) {
                    if (count($defaultValue) === 0) {
                        $valSrc .= 'array()';
                    } else {
                        return false;
                    }
                } else if (is_null($defaultValue)) {
                    $valSrc .= 'null';
                } else if (is_bool($defaultValue)) {
                    $valSrc .= $defaultValue ? 'true' : 'false';
                } else if (is_numeric($defaultValue)){
                    $valSrc .= $defaultValue;
                } else {
                    return false;
                }
                $paramSrc .= ' = ' . $valSrc;
            }
            $paramSrcs[] = $paramSrc;
        }
        return $src . implode(', ', $paramSrcs) . ') {';
    }

    /**
     * @param ReflectionClass 
     * @param array Interceptors 
     */
    public static function generateInternal($concreteClassName, ReflectionClass $targetClass, array $applicableMethods)
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
    public $methodInterceptorsMap_EnhancedByS2AOP = array();
    public $parameters_EnhancedByS2AOP = null;' . PHP_EOL;
        $abstractMethods = array();
        foreach ($applicableMethods as $methodRef) {
            $methodDef = self::getMethodDefSrc($methodRef);
            if ($methodDef === false) {
                S2Container_S2Logger::getLogger(__CLASS__)->info("cannot parse param [{$methodRef->getDeclaringClass()->getName()}::{$methodRef->getName()}()]",__METHOD__);
                continue;
            }
            if ($methodRef->isAbstract()) {
                $abstractMethods[] = $methodRef->getName();
            }

            $srcLine .= self::getMethodSrc($targetClass, $methodRef, $methodDef);
            $srcLine .= PHP_EOL;
        }
        $srcLine .= self::addInvokeParentMethodSrc($abstractMethods);
        $srcLine .= PHP_EOL;
        $srcLine .= self::addInvokeProceedMethodSrc();
        $srcLine .= '}' . PHP_EOL;
        return $srcLine;
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
    }

    public static function addInvokeProceedMethodSrc()
    {
        $methodContent = '    private function __invokeMethodInvocationProceed_EnhancedByS2AOP() {
        $args = func_get_args();
        $methodName = array_pop($args);
        $methodInvocation = new S2Container_S2MethodInvocationImpl($this, $this->clazz_EnhancedByS2AOP, $this->clazz_EnhancedByS2AOP->getMethod($methodName), $args, $this->methodInterceptorsMap_EnhancedByS2AOP[$methodName], $this->parameters_EnhancedByS2AOP, $this->concreteClazz_EnhancedByS2AOP, $this->concreteClazz_EnhancedByS2AOP->getMethod($methodName . \'_EnhancedByS2AOP\'));
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
