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
// $Id: S2Container_AopProxyGenerator.class.php 255 2006-05-23 14:51:12Z klove $
/**
 * @package org.seasar.framework.aop.proxy
 * @author klove
 * @author nowel
 */
class S2Container_EnhancedClassGenerator
{
    const CLASS_NAME_PREFIX = '';
    const CLASS_NAME_POSTFIX = 'EnhancedByS2AOP';
    
    const DEF_CLASS_NAME = 0;
    const DEF_INTERFACE = 1;
    const DEF_CONST = 4;
    const DEF_PROPERTY = 5;
    const DEF_METHOD = 6;
    
    /**
     * 
     */
    private $target = null;
    /**
     * 
     */
    private $targetClass = null;
    /**
     * 
     */
    private $targetClassName = '';
    /**
     * 
     */    
    protected $enhancedClassName = '';
    /**
     * 
     */
    protected $enhancedClassGenerator = null;
    /**
     * 
     */
    protected $parameter = null;
    /**
     * Class Sources
     */
    protected $source = array();
    /**
     * 
     */
    protected $evalute = array();

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
    
    public function initialize(){
        $this->evalute[self::DEF_CLASS_NAME] = '';
        $this->evalute[self::DEF_INTERFACE] = array();
        $this->evalute[self::DEF_CONST] = array();
        $this->evalute[self::DEF_PROPERTY] = array();
        $this->evalute[self::DEF_METHOD] = array();
        
        $this->setupClass();
        $this->setupInterface();
        $this->setupConstructor();
    }
    
    /**
     * 
     */
    protected function setupClass(){
        $this->enhancedClassName = $this->getConcreteClassName();
        $className = str_replace($this->targetClassName,
                                 $this->enhancedClassName,
                                 $this->source[0]);
        $className = str_replace('{', '', $className);
        $this->evalute[self::DEF_CLASS_NAME] = $className;
    }
    
    /**
     * 
     */
    protected function setupInterface(){
        $interfaces = S2Container_ClassUtil::getInterfaces($this->targetClass); 
        foreach ($interfaces as $interface) {
            $this->addInterface($interface->getName());
        }
    }
    
    protected function setupConstructor(){
    }
    
    /**
     * 
     */
    public function addInterface($interfaceName){
        $this->evalute[self::DEF_INTERFACE][] = $interfaceName;
    }
    
    /**
     * 
     */
    public function addConstant($name, $value){
        $this->evalute[self::DEF_CONST] = array();
    }

    /**
     * 
     */
    public function addProperty($modify, $name){
        $property = 'public ';
        $this->evalute[self::DEF_PROPERTY] = array(); 
    }

    /**
     * 
     */
    public function addMethod($modify, $name, $src){
        $method = 'public ';
        if($modify == S2Container_InterType::STATIC_){
            $method .= 'static ';
        }
        $method .= 'function ' . $name . $src;
        $this->evalute[self::DEF_METHOD][] = $method;
    }
    
    /**
     * 
     */
    private function getClassName(){
        return $this->evalute[self::DEF_CLASS_NAME] . PHP_EOL;
    }
    
    /**
     * 
     */
    private function getInterface(){
        $interfaces = $this->evalute[self::DEF_INTERFACE];
        $implLine = '';
        if (count($interfaces) > 0) {
            $implLine = ' implements ' . implode(',', $interfaces);
        }
        return $implLine . '{' . PHP_EOL;
    }
    
    /**
     * 
     */
    private function getProperty(){
        return implode(PHP_EOL, $this->evalute[self::DEF_PROPERTY]);
    }
    
    /**
     * 
     */
    private function getConstant(){
        return implode(PHP_EOL, $this->evalute[self::DEF_CONST]);
    }
    
    /**
     * 
     */
    private function getMethod(){
        return implode(PHP_EOL, $this->evalute[self::DEF_METHOD]);
    }
    
    /**
     * 
     */
    protected function getEvaluteSource(){
        $srcLine = $this->getClassName();
        $srcLine .= $this->getInterface();
        $srcLine .= $this->getConstant();
        $srcLine .= $this->getProperty();
        $srcLine .= $this->getMethod();
        
        $o = count($this->source) - 1;
        for ($i = 1; $i < $o; $i++) {
            $srcLine .= str_replace($this->targetClassName,
                        $this->enhancedClassName,
                        $this->source[$i]);
        }
        
        return $srcLine . '}' . PHP_EOL;
    }

    /**
     * 
     */
    public function applyInterType(S2Container_InterType $interType) {
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
        
        $srcLine = $this->getEvaluteSource();
        
        if (S2Container_FileCacheUtil::isAopCache()) {
            S2Container_FileCacheUtil::saveAopCache($this->enhancedClassName, $srcLine);
        }

        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') && S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->debug("[ $srcLine ]",__METHOD__);
        }
        eval($srcLine);
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
    public function setInterTypes(array $interTypes = null) {
        if ($interTypes === null || count($interTypes) == 0) {
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
    public function setInterceptors(ReflectionMethod $method, array $interceptors) {
    }
    
    /**
     * 
     */
    public function getMethodInvocationClassName(ReflectionMethod $method) {
    }

    /**
     * 
     */
    public function createInvokeSuperMethod(ReflectionMethod $method) {
    }

    /**
     * 
     */
    public function setStaticField(ReflectionClass $clazz, $name, $value) {
    }
}
?>
