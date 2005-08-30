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
 * Aspect‚ð“K—p‚µ‚½Proxy‚ðì¬‚µ‚Ü‚·B
 * 
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
final class AopProxy {
	private $log_;
    private $targetClass_;
    private $enhancedClass_;
    private $defaultPointcut_;
    private $parameters_;
    private $methodInterceptorsMap_;

    public function AopProxy($targetClass,$aspects,$parameters=null) {
		$this->log_ = S2Logger::getLogger(get_class($this));

        $this->parameters_ = $parameters;
        if($targetClass instanceof ReflectionClass){ 
            $this->setTargetClass($targetClass);
        }else{
            $this->setTargetClass(new ReflectionClass($targetClass));
        }
        $this->setAspects($aspects);
    }

    private function setTargetClass($targetClass) {
        $this->targetClass_ = $targetClass;
        $this->defaultPointcut_ = new PointcutImpl($targetClass);
    }

    private function setAspects($aspects) {
        if ($aspects == null || count($aspects) == 0) {
            throw new EmptyRuntimeException("aspects");
        }

        for ($i = 0; $i < count($aspects); ++$i) {
            $aspect = $aspects[$i];
            if ($aspect->getPointcut() == null) {
                $aspect->setPointcut($this->defaultPointcut_);
            }
        }
        
        $methods = $this->targetClass_->getMethods();
        $this->methodInterceptorsMap_ = array();
        for ($i = 0;$i < count($methods); ++$i) {
        	if(!AopProxy::isApplicableAspect($methods[$i])){
        		$this->log_->info($this->targetClass_->getName()."::".
        		                   $methods[$i]->getName() ."() is a constructor or a static method. ignored.",__METHOD__);
                continue;        		                  
        	}
        	
            $interceptorList = array();
            for ($j = 0; $j < count($aspects); ++$j) {
                $aspect = $aspects[$j];
                if ($aspect->getPointcut()->isApplied($methods[$i]->getName())) {
                    array_push($interceptorList,$aspect->getMethodInterceptor());
                }else{
                    $this->log_->info("no pointcut defined for " . 
                        $this->targetClass_->getName() . "::" .
                        $methods[$i]->getName() . "()",__METHOD__);
                }
            }
            
            if(count($interceptorList) > 0){
                $this->methodInterceptorsMap_[$methods[$i]->getName()] = $interceptorList;
            }
        }
    }

    public function getEnhancedClass() {
        return $this->enhancedClass_;
    }

    public function create($argTypes=null,$args=null) {

        if($this->targetClass_->isFinal()){
        	throw new S2RuntimeException('ESSR0017',
        	                               array("cannot aspect. target class [{$this->targetClass_->getName()}] is final class. "));
        }
        $this->enhancedClass_ = UuCallAopProxyFactory::create(
                                    $this->targetClass_,
                                    $this->methodInterceptorsMap_,
                                    $args,
                                    $this->parameters_);
        return $this->enhancedClass_;

    }

    public static function isApplicableAspect(ReflectionMethod $method) {
    	return ! $method->isStatic() and ! $method->isConstructor();
    }
}
?>