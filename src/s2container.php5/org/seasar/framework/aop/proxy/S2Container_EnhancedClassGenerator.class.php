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

    /**
     * 
     */
    public function __construct($target, $targetClass, $params = null)
    {
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->targetClassName = $targetClass->getName();
        $this->parameters = $params;
        $this->source = S2Container_ClassUtil::getClassSource($targetClass);
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
        
        $this->setupClass();
        $this->setupInterface();
        $this->setupConstructor();
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
            $this->addInterface($interface->getName());
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
        $const .= ' = ' . $value;
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
            $src .= str_replace($this->targetClassName,
                        $this->enhancedClassName,
                        $this->source[$i]);
        }
        
        return $src . '}' . PHP_EOL;
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
    public function generate()
    {
        if (class_exists($this->enhancedClassName, false)) {
            return $this->enhancedClassName;
        }

        if (S2Container_FileCacheUtil::isAopCache()) {
            if (S2Container_FileCacheUtil::loadAopCache($this->enhancedClassName,
               $targetClass->getFileName())) {
                return $this->enhancedClassName;
            }
        }
        
        $source = $this->getevaluateSource();
        
        if (S2Container_FileCacheUtil::isAopCache()) {
            S2Container_FileCacheUtil::saveAopCache($this->enhancedClassName, $source);
        }

        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') && S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->debug("[ $srcLine ]",__METHOD__);
        }
        eval($source);
        return $this->enhancedClassName;
    }

    /**
     */
    public function getConcreteClassName()
    {
        return self::CLASS_NAME_PREFIX .
               $this->targetClass->getName() . 
               self::CLASS_NAME_POSTFIX;
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
            $this->applyInterType(new $it($this));
        }
    }
    
    /**
     * 
     */
    public function setInterceptors(array $interceptors = null)
    {
        $this->createInvocationSetter();
        if (null === $interceptors || 0 == count($interceptors)) {
            return;
        }
        
        foreach($interceptors as $methodName => $interceptorList){
            $invokeName = $methodName;
            if($this->targetClass->hasMethod($methodName)){
                $invokeName = $this->getInvocationMethodName($methodName);
                for($i = 0; $i < count($this->source); $i++){
                    $this->source[$i] = str_replace($methodName, $invokeName,
                                                    $this->source[$i]);
                }
            }
            $this->addMethod(S2Container_InterType::PUBLIC_,
                             $invokeName,
                             $this->createInvokeMethodInterceptor($methodName));
        }
        $this->addMethod(S2Container_InterType::PUBLIC_,
                         '__call',
                         $this->createInvokeSuperMethod());

    }
    
    /**
     * 
     */
    private function createInvocationSetter()
    {
        $propModify = S2Container_InterType::PRIVATE_;
        $properties = array();
        $_target = $this->getInvocationTargetPropery();
        $_targetClass = $this->getInvocationTargetClassProperty();
        $_map = $this->getInvocationMapProperty();
        $_param = $this->getInvocationParamProperty();
        
        $this->addProperty($propModify, $_target);
        $this->addProperty($propModify, $_targetClass);
        $this->addProperty($propModify, $_map);
        $this->addProperty($propModify, $_param);
        
        $srcMethod = array();
        $srcMethod[] = '($target, $targetClass, $map, $property){';
        $srcMethod[] = '    $this->' . $_target . ' = $target;';
        $srcMethod[] = '    $this->' . $_targetClass . ' = $targetClass;';
        $srcMethod[] = '    $this->' . $_map . ' = $map;';
        $srcMethod[] = '    $this->' . $_param . ' = $property;';
        $srcMethod[] = '    $this->' . $_param .
                       '[S2Container_ContainerConstants::S2AOP_PROXY_NAME] = $this;';
        $srcMethod[] = '}';
        
        $methodModify = S2Container_InterType::PUBLIC_;
        $methodName = self::INVOKE_SUPER_METHOD_SUFFIX;
        $this->addMethod($methodModify, $methodName, implode(PHP_EOL, $srcMethod));
    }
    
    private function getInvocationMethodName($methodName)
    {
        return self::INVOKE_SUPER_METHOD_SUFFIX . $methodName;
    }
    
    /**
     * 
     */
    private function getInvocationTargetPropery()
    {
        return self::INVOKE_SUPER_METHOD_SUFFIX . 'target';
    }
    
    /**
     * 
     */
    private function getInvocationTargetClassProperty()
    {
        return self::INVOKE_SUPER_METHOD_SUFFIX . 'targetClass';
    }
    
    /**
     * 
     */
    private function getInvocationMapProperty()
    {
        return self::INVOKE_SUPER_METHOD_SUFFIX . 'Map';
    }
    
    /**
     * 
     */
    private function getInvocationParamProperty()
    {
        return self::INVOKE_SUPER_METHOD_SUFFIX . 'Param';
    }
    
    /**
     * 
     */
    public function getInvokeMethodInterceptor($methodName)
    {
        $src = array();
        $src[] = '(){';
        $src[] = '    $args = func_get_args();';
        $src[] = '    $name = "' . $methodName . '";';
        $src[] = '    $methodInvocation = ';
        $src[] = '    new S2Container_S2MethodInvocationImpl(';
        $src[] = '        $this->' . $this->getInvocationTargetPropery() . ',';
        $src[] = '        $this->' . $this->getInvocationTargetClassProperty() . ',';
        $src[] = '        $this->' . $this->getInvocationTargetClassProperty() . '->getMethod($name),';
        $src[] = '        $args,';
        $src[] = '        $this->' . $this->getInvocationMapProperty() . '[$name],';
        $src[] = '        $this->' . $this->getInvocationParamProperty() . ');';
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
        $src[] = '    $this->' . $this->getInvocationTargetClassProperty() . '->getMethod($name),';
        $src[] = '    $this->' . $this->getInvocationTargetPropery() . ',';
        $src[] = '    $args);';
        return implode(PHP_EOL, $src);
    }

}
?>
