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
final class S2Container_AopProxyFactory
{
    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     * 
     */
    public function create($target = null,
                           $targetClass,
                           $aspects,
                           $parameters = null)
    {
        //$log = S2Container_S2Logger::getLogger('S2Container_AopProxyFactor');

        if (!$targetClass instanceof ReflectionClass) {
            if (is_string($targetClass)) {
                $targetClass = new ReflectionClass($targetClass);
            } else if (is_object($target)) {
                $targetClass = new ReflectionClass($target);
            } else {
                throw new S2Container_S2RuntimeException('ESSR1010',
                    array($target,$targetClass));
            }
        }

        if (!$targetClass->isUserDefined() or
             $targetClass->hasMethod('__call')) {
            return $target;
        }

        $methodInterceptorsMap = 
            self::_creatMethodInterceptorsMap($targetClass,
                                              $aspects);

        $interfaces = S2Container_ClassUtil::getInterfaces($targetClass); 
        if (count($interfaces) == 0) {
            return new S2Container_DefaultAopProxy($target,
                                                   $targetClass,
                                                   $methodInterceptorsMap,
                                                   $parameters);
        }

        $concreteClassName = S2Container_AopProxyGenerator::generate($target,
                                                $targetClass,
                                                $parameters);
        return new $concreteClassName($target,
                                      $targetClass,
                                      $methodInterceptorsMap,
                                      $parameters);
    }

    /**
     * @param ReflectionClass
     * @param array Aspect array
     */
    private function _creatMethodInterceptorsMap($targetClass,$aspects)
    {
        if ($aspects == null || count($aspects) == 0) {
            throw new S2Container_EmptyRuntimeException("aspects");
        }

        $defaultPointcut = new S2Container_PointcutImpl($targetClass);
        $c = count($aspects);
        for ($i = 0; $i < $c; ++$i) {
            if ($aspects[$i]->getPointcut() == null) {
                $aspects[$i]->setPointcut($defaultPointcut);
            }
        }
        
        $methods = $targetClass->getMethods();
        $methodInterceptorsMap = array();
        $o = count($methods);
        for ($i = 0; $i < $o; ++$i) {
            if (!S2Container_AopProxyFactory::isApplicableAspect($methods[$i])) {
/*
                S2Container_S2Logger::getLogger(__CLASS__)->
                    info($targetClass->getName()."::".
                           $methods[$i]->getName() .
                           "() is a constructor or a static method. ignored.",
                           __METHOD__);
*/
                continue;
            }
        
            $interceptorList = array();
            $p = count($aspects);
            for ($j = 0; $j < $p; ++$j) {
                $aspect = $aspects[$j];
                if ($aspects[$j]->getPointcut()->isApplied($methods[$i]->getName())) {
                    array_push($interceptorList,$aspects[$j]->getMethodInterceptor());
                }
/*
                else{
                    S2Container_S2Logger::getLogger(__CLASS__)->
                    info("no pointcut defined for " . 
                        $targetClass->getName() . "::" .
                        $methods[$i]->getName() . "()",__METHOD__);
                }
*/                
            }
            
            if (count($interceptorList) > 0) {
                $methodInterceptorsMap[$methods[$i]->getName()] = $interceptorList;
            }
        }
        return $methodInterceptorsMap;
    }

    /**
     * @param ReflectionMethod
     */
    public static function isApplicableAspect(ReflectionMethod $method)
    {
        return $method->isPublic() and
               !$method->isStatic() and 
               !$method->isConstructor();
    }
}
?>
