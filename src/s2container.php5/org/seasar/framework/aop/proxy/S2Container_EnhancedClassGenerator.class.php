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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.aop.proxy
 * @author nowel
 */
class S2Container_EnhancedClassGenerator
{
    /** */
    const PROXY_CLASS = 'S2Container_EnhancedClassAopProxy';
    /** */
    const CLASS_NAME_PREFIX = '';
    /** */
    const CLASS_NAME_POSTFIX = 'EnhancedByS2AOP';
    /** */
    const INVOKE_SUPER_METHOD_SUFFIX = 'invokeSuperMethod';
    
    /** */
    const DEF_CLASS_NAME = 0;
    /** */
    const DEF_INTERFACE = 1;
    /** */
    const DEF_CONST = 4;
    /** */
    const DEF_PROPERTY = 5;
    /** */
    const DEF_METHOD = 6;
    
    /** */
    private $target = null;
    /** */
    private $targetClass = null;
    /** */
    private $targetClassName = '';
    /** */
    protected $enhancedClassName = '';
    /** */
    protected $parameters = array();
    /** Target Class Sources */
    protected $source = array();
    /** Evaluate Sources */
    protected $evaluate = array();
    
    private static $instancate = 0; 

    /**
     * 
     */
    public function __construct($target, $targetClass, $params = null)
    {
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->targetClassName = $targetClass->getName();
        $this->parameters = $params;
        
        $this->initialize();
    }
    
    /**
     * 
     */
    public function initialize()
    {
        $this->evaluate[self::DEF_CLASS_NAME] = '';
        $this->evaluate[self::DEF_INTERFACE] = array();
        $this->evaluate[self::DEF_CONST] = array();
        $this->evaluate[self::DEF_PROPERTY] = array();
        $this->evaluate[self::DEF_METHOD] = array();
        
        $proxy = new ReflectionClass(self::PROXY_CLASS);
        $this->source = S2Container_ClassUtil::getClassSource($proxy);
        
        $this->setupClass();
        $this->setupInterface();
        $this->setupConstructor();
        $this->setupProperty();
    }
    
    /**
     * 
     */
    protected function setupClass()
    {
        $this->enhancedClassName = $this->getConcreteClassName();
        $this->evaluate[self::DEF_CLASS_NAME] = $this->enhancedClassName;
    }
    
    /**
     * 
     */
    protected function setupInterface()
    {
        $interfaces = S2Container_ClassUtil::getInterfaces($this->targetClass); 
        foreach ($interfaces as $interface) {
            $interfaceSrc = S2Container_ClassUtil::getSource($interface);
            
            $iName = $interface->getName();
            $this->addInterface($iName);
            
            $methods = $interface->getMethods();
            $unApplicable = false;
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() != $iName) {
                    continue;
                }
                if (!S2Container_AopProxyFactory::isApplicableAspect($method)) {
                    $unApplicable = true;
                    break;
                }
                $this->evaluate[self::DEF_METHOD][] =
                    S2Container_AopProxyGenerator::getMethodDefinition($method, $interfaceSrc);
            }
        }
    }
    
    /**
     * 
     */
    protected function setupConstructor()
    {
    }
    
    /**
     * 
     */
    protected function setupProperty()
    {
        $properties = $this->targetClass->getProperties();
        foreach($properties as $property){
            if($property->isPublic()){
                continue;
            }
            
            $modify = array();
            if($property->isProtected()){
                $modify[] = S2Container_InterType::PROTECTED_;
            } else {
                $modify[] = S2Container_InterType::PRIVATE_;
            }
            if($property->isStatic()){
                $modify[] = S2Container_InterType::STATIC_;
            }
            $this->addProperty(implode('', $modify), $property->getName());
        }
        $constants = $this->targetClass->getConstants();
        foreach($constants as $name => $value){
            $this->addConstant($name, $value);
        }
    }
    
    /**
     * 
     */
    public function addInterface($interfaceName)
    {
        $this->evaluate[self::DEF_INTERFACE][] = $interfaceName;
    }
    
    /**
     * 
     */
    public function addConstant($name, $value)
    {
        $const = S2Container_InterType::CONST_ . $name;
        $const .= ' = ' . $value . ';';
        $this->evaluate[self::DEF_CONST][] = $const;
    }

    /**
     * 
     */
    public function addProperty($modify, $name, $defaultValue = null)
    {
        $property = $modify . ' $' . $name;
        if($defaultValue !== null){
            $property .= ' = ' . $defaultValue;
        }
        $property .= ';';
        $this->evaluate[self::DEF_PROPERTY][] = $property; 
    }

    /**
     * 
     */
    public function addMethod($modify, $name, $src)
    {
        $method = $modify . ' function ' . $name . $src;
        $this->evaluate[self::DEF_METHOD][] = $method;
    }
    
    /**
     * 
     */
    private function getClassName()
    {
        return 'class ' . $this->evaluate[self::DEF_CLASS_NAME];
    }
    
    /**
     * 
     */
    private function getInterface()
    {
        $interfaces = $this->evaluate[self::DEF_INTERFACE];
        $implLine = '';
        if (count($interfaces) > 0) {
            $implLine = ' implements ' . implode(',', $interfaces);
        }
        return $implLine . ' {';
    }
    
    /**
     * 
     */
    private function getProperty()
    {
        return implode(PHP_EOL, $this->evaluate[self::DEF_PROPERTY]);
    }
    
    /**
     * 
     */
    private function getConstant()
    {
        return implode(PHP_EOL, $this->evaluate[self::DEF_CONST]);
    }
    
    /**
     * 
     */
    private function getMethod()
    {
        return implode(PHP_EOL, $this->evaluate[self::DEF_METHOD]);
    }
    
    /**
     * 
     */
    protected function getevaluateSource()
    {
        $srcLine = array();
        $srcLine[] = $this->getClassName();
        $srcLine[] = $this->getInterface();
        $srcLine[] = $this->getConstant();
        $srcLine[] = $this->getProperty();
        $srcLine[] = $this->getMethod();
        
        $src = implode(PHP_EOL, $srcLine);
        $o = count($this->source) - 1;
        for ($i = 1; $i < $o; $i++) {
            $src .= str_replace(self::PROXY_CLASS,
                        $this->enhancedClassName,
                        $this->source[$i]);
        }
        
        return $src . '}' . PHP_EOL;
    }

    /**
     * 
     */
    public function generate()
    {
        // TODO: 同一のEnhanceを返したい場合はどうするか
        if (class_exists($this->enhancedClassName, false)) {
            return $this->enhancedClassName;
        }
        
        $support = S2Container_CacheSupportFactory::create();
        if (!$support->isAopProxyCaching($this->targetClass->getFileName())) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                    debug("set caching off.", __METHOD__);
            $this->evalInternal($this->getevaluateSource());
            return $this->enhancedClassName;
        }
        
        if ($srcLine = $support->loadAopProxyCache($this->targetClass->getFileName())) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                    debug("cached aop proxy found.", __METHOD__);
            $this->evalInternal($srcLine);
            return $this->enhancedClassName;
        }
        
        S2Container_S2Logger::getLogger(__CLASS__)->
                    debug("create aop proxy and cache it.", __METHOD__);
        $srcLine = $this->getevaluateSource();
        $support->saveAopProxyCache($srcLine, $this->targetClass->getFileName() . $this->enhancedClassName);
        $this->evalInternal($srcLine);
        return $this->enhancedClassName;
    }
    
    /**
     * 
     */
    private function evalInternal($srcLine) {
        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') and S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->
                    debug("[ $srcLine ]",__METHOD__);
        }
        eval($srcLine);
    }

    /**
     */
    public function getConcreteClassName()
    {
        return self::CLASS_NAME_PREFIX .
               $this->targetClass->getName() . 
               self::CLASS_NAME_POSTFIX .
               self::$instancate++;
    }
    
    /**
     * 
     */
    public function applyInterType(S2Container_InterType $interType)
    {
        $interType->introduce($this->targetClass, $this->enhancedClassName);
    }
    
    /**
     * 
     */
    public function setInterTypes(array $interTypes = null)
    {
        if (null === $interTypes || 0 == count($interTypes)) {
            return;
        }

        $c = count($interTypes);
        for ($i = 0; $i < $c; ++$i) {
            $it = $interTypes[$i];
            if($it instanceof S2Container_InterTypeChain){
                $it->setEnhancedClass($this);
                $this->applyInterType($it);
            } else {
                $this->applyInterType(new $it($this));
            }
        }
    }
    
    /**
     * 
     */
    public function setInterceptors(array $interceptors = null)
    {
        if (null === $interceptors || 0 == count($interceptors)) {
            return;
        }
        
        foreach($interceptors as $methodName => $interceptorList){
            $invokeName = $methodName;
            if($this->targetClass->hasMethod($methodName)){
                continue;
            }
            $this->addMethod(S2Container_InterType::PUBLIC_,
                             $methodName,
                             $this->createInvokeMethodInterceptor($methodName));
        }
    }
    
    /**
     * 
     */
    public function createInvokeMethodInterceptor($methodName)
    {
        $src = array();
        $src[] = '(){';
        $src[] = '    $args = func_get_args();';
        $src[] = '    $name = "' . $methodName . '";';
        $src[] = '    $methodInvocation = ';
        $src[] = '    new S2Container_S2MethodInvocationImpl(';
        $src[] = '        $this->target_,';
        $src[] = '        $this->targetClass_,';
        $src[] = '        $this->targetClass_->getMethod($name),';
        $src[] = '        $args,';
        $src[] = '        $this->methodInterceptorsMap_[$name],';
        $src[] = '        $this->parameters_);';
        $src[] = '    return $methodInvocation->proceed();';
        return implode(PHP_EOL, $src);
    }
    
    /**
     * 
     */
    public function createInvokeSuperMethod()
    {
        $src = array();
        $src[] = '($name, $args){';
        $src[] = 'return S2Container_MethodUtil::invoke(';
        $src[] = '    $this->targetClass_->getMethod($name),';
        $src[] = '    $this->target_,';
        $src[] = '    $args);';
        return implode(PHP_EOL, $src);
    }

}
?>
