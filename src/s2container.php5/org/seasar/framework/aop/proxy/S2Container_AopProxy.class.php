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
final class S2Container_AopProxy
{
    private $log_;
    private $targetClass_;
    private $enhancedClass_;
    private $defaultPointcut_;
    private $parameters_;
    private $methodInterceptorsMap_;

    /**
     * @param ReflectionClass
     * @param array Aspect array
     * @param array 
     */
    public function __construct($targetClass,$aspects,$parameters = null)
    {
        $this->log_ = S2Container_S2Logger::getLogger(get_class($this));

        $this->parameters_ = $parameters;
        if ($targetClass instanceof ReflectionClass) { 
            $this->_setTargetClass($targetClass);
        } else {
            $this->_setTargetClass(new ReflectionClass($targetClass));
        }
        $this->_setAspects($aspects);
    }

    /**
     * @param ReflectionClass 
     */
    private function _setTargetClass($targetClass)
    {
        $this->targetClass_ = $targetClass;
        $this->defaultPointcut_ = new S2Container_PointcutImpl($targetClass);
    }

    /**
     * @param array Aspect array
     */
    private function _setAspects($aspects)
    {
        if ($aspects == null || count($aspects) == 0) {
            throw new S2Container_EmptyRuntimeException("aspects");
        }

        $o = count($aspects);
        for ($i = 0; $i < $o; ++$i) {
            $aspect = $aspects[$i];
            if ($aspect->getPointcut() == null) {
                $aspect->setPointcut($this->defaultPointcut_);
            }
        }
        
        $methods = $this->targetClass_->getMethods();
        $this->methodInterceptorsMap_ = array();
        $o = count($methods);
        for ($i = 0; $i < $o; ++$i) {
            if (!S2Container_AopProxy::isApplicableAspect($methods[$i])) {
                $this->log_->info($this->targetClass_->getName() . "::" .
                    $methods[$i]->getName() .
                    "() is a constructor or a static method. ignored.",
                    __METHOD__);
                continue;
            }

            $interceptorList = array();
            $p = count($aspects);
            for ($j = 0; $j < $p; ++$j) {
                $aspect = $aspects[$j];
                if ($aspect->getPointcut()->isApplied($methods[$i]->getName())) {
                    array_push($interceptorList,$aspect->getMethodInterceptor());
                } else {
                    $this->log_->info("no pointcut defined for " . 
                        $this->targetClass_->getName() . "::" .
                        $methods[$i]->getName() . "()",__METHOD__);
                }
            }
            
            if (count($interceptorList) > 0) {
                $this->methodInterceptorsMap_[$methods[$i]->getName()] = $interceptorList;
            }
        }
    }

    /**
     * @param object
     */
    public function getEnhancedClass()
    {
        return $this->enhancedClass_;
    }

    /**
     * @param array
     * @param array
     */
    public function create($argTypes = null,$args = null)
    {
        if ($this->targetClass_->isFinal()) {
            throw new S2Container_S2RuntimeException('ESSR0017',
            array("cannot aspect. target class [" . 
                   $this->targetClass_->getName() . 
                  "] is final class. "));
        }
        $this->enhancedClass_ = 
            S2Container_UuCallAopProxyFactory::create($this->targetClass_,
                                    $this->methodInterceptorsMap_,
                                    $args,
                                    $this->parameters_);
        return $this->enhancedClass_;

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
