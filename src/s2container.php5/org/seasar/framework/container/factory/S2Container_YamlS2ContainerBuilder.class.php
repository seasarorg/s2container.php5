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
// $Id: S2Container_YamlS2ContainerBuilder.class.php 222 2006-03-07 09:58:42Z klove $

/**
 * @package org.seasar.framework.container.factory
 * @author nowel
 * @version test
 */
final class S2Container_YamlS2ContainerBuilder
    implements S2ContainerBuilder
{

    // {{{ properties
    const regex_php_value = '/^\((.+)\)$/';
    private $container = null;
    private $spyc = null;
    // }}}

    // {{{ constructor
    /**
     * require Spyc
     */
    public function __construct()
    {
        if(!class_exists("Spyc")){
            throw new Exception("required Spyc");
        }
        
        $this->spyc = new Spyc();
    }
    // }}}
    
    public function includeChild(S2Container $parent, $path) 
    {
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }

    public function build($path)
    {
        $container = new S2ContainerImpl();
        $container->setPath($path);

        $root = $this->_loadDicon($path);
        $this->_setupNameSpace($container, $root);
        $this->_setupInclude($container, $root);

        $this->container = $container;
        S2Container_ChildComponentDefBindingUtil::init();

        foreach($root['component'] as $component){
            $this->_setupComponentDef($component);
        }
        
        $this->_setupMetaDef($root, $this->container);
        S2Container_ChildComponentDefBindingUtil::bind($this->container);

        return $this->container;
    }
    
    private function _loadDicon($path){
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001', array($path));
        }

        $root = null;
        $yaml = $this->spyc->load($path);

        if(isset($yaml['components'])){
            $root = $yaml['components'];
        } else {
            $root = $yaml[0];
        }
        
        return $root;
    }
    
    private function _setupNameSpace(S2Container $container, &$root){
        if (isset($root['namespace'])) {
            $container->setNamespace($root['namespace']); 
            unset($root['namespace']);
        }
    }

    private function _setupInclude(S2Container $container, &$root){
        if(isset($root['include'])){
            foreach($root['include'] as $path){
                $path = S2Container_StringUtil::expandPath($path);
                if (!is_readable($path)) {
                    throw new S2Container_S2RuntimeException('ESSR0001',array($path));
                }
                $child = S2ContainerFactory::includeChild($container, $path);
                $child->setRoot($container->getRoot());
            }
            unset($root['include']);
        }
    }

    private function _setupComponentDef(array $component)
    {
        $className = $component['class'];
        $componentDef = $this->_createComponentDef($component, $className);
        $this->container->register($componentDef);

        unset($component['class'], $component['name']);
        foreach($component as $tag){
            $this->_setupDefs($componentDef, $tag, $className);
        }
        
        return $componentDef;        
    }

    private function _setupDefs($componentDef, array $component, $className){
        $name = $this->getName($component);
        unset($component['name']);

        if(isset($component['arg'])){
            $val = (array)$component['arg'];
            $componentDef->addArgDef($this->_setupArgDef($val));
        }
        
        if(isset($component['property'])){
            unset($component['property']);
            $propertyDef = $this->_setupPropertyDef($component, $name);
            $componentDef->addPropertyDef($propertyDef);
        }
        
        if(isset($component['initMethod'])){
            unset($component['initMethod']);
            $initMethodDef = $this->_setupInitMethodDef($component, $name);
            $componentDef->addInitMethodDef($initMethodDef);
        }

        if(isset($component['destroyMethod'])){
            unset($component['destroyMethod']);
            $destroyMethodDef = $this->_setupDestroyMethodDef($component, $name);
            $componentDef->addDestroyMethodDef($destroyMethodDef);
        }
        
        if(isset($component['aspect'])){
            $val = (array)$component['aspect'];
            $componentDef->addAspectDef($this->_setupAspectDef($val, $className));
        }
        
        $this->_setupMetaDef($component, $componentDef);
    }

    private function _createComponentDef(array $component, $className)
    {
        $name = $this->getName($component);
        $componentDef = new S2Container_ComponentDefImpl($className, $name);
        $componentDef->setExpression('');

        if (isset($component['instance'])) {
            $componentDef->setInstanceMode($component['instance']);
        }

        if (isset($component['autoBinding'])) {
            $componentDef->setAutoBindingMode($component['autoBinding']);
        }
        
        return $componentDef;
    }
    
    private function _setupArgDef(array $arg)
    {
        $argDef = new S2Container_ArgDefImpl();
        $this->_setupArgDefInternal($arg, $argDef);
        $this->_setupMetaDef($arg, $argDef);
        return $argDef;
    }

    private function _setupArgDefInternal(array $arg, S2Container_ArgDef $argDef)
    {
        if(!isset($arg['component'])){
            $argValue = $arg[0];
            $injectValue = $this->_getInjectionValue($argValue);
            if ($injectValue == null){
                $argDef->setValue($argValue);
            } else {
                $argDef->setExpression($injectValue);
                S2Container_ChildComponentDefBindingUtil::put($injectValue, $argDef);
            }
        } else {
            $childComponent = $this->_setupComponentDef($arg['component']);
            $argDef->setChildComponentDef($childComponent);
        }
    }

    private function _setupPropertyDef(array $property, $name)
    {
        $propertyDef = new S2Container_PropertyDefImpl($name);
        $this->_setupArgDefInternal($property, $propertyDef);
        $this->_setupMetaDef($property, $propertyDef);
        return $propertyDef;
    }
    
    private function _setupInitMethodDef(array $initMethod, $name)
    {
        $initMethodDef = new S2Container_InitMethodDefImpl($name);
        $this->_setupMethodDefInternal($initMethod, $initMethodDef);
        return $initMethodDef;
    }

    private function _setupDestroyMethodDef(array $destroyMethod, $name)
    {
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);
        $this->_setupMethodDefInternal($destroyMethod, $destroyMethodDef);
        return $destroyMethodDef;
    }

    private function _setupMethodDefInternal(array $method, S2Container_MethodDef $methodDef)
    {
        if (isset($method[0]) && is_string($method[0])) {
            $methodDef->setExpression($method[0]);
        }
        
        foreach($method as $val){
            if(isset($val['arg'])){
                $arg = (array)$val['arg'];
                $methodDef->addArgDef($this->_setupArgDef($arg));
            }
        }
    }

    private function _setupAspectDef(array $aspect, $targetClassName)
    {
        if (isset($aspect['pointcut'])) {
            $pointcuts = split(',', $aspect['pointcut']);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        } else {
            $pointcut = new S2Container_PointcutImpl($targetClassName);
        }
        
        $aspectDef = new S2Container_AspectDefImpl($pointcut);
        if (!isset($aspect['component'])) {
            $aspectValue = $aspect[0];
            $injectValue = $this->_getInjectionValue($aspectValue);
            if ($injectValue != null){
                 $aspectValue = $injectValue;
            }
            $aspectDef->setExpression($aspectValue);
            S2Container_ChildComponentDefBindingUtil::put($aspectValue,$aspectDef);
        } else {
            $childComponent = $this->_setupComponentDef($aspect['component']);
            $aspectDef->setChildComponentDef($childComponent);
        }
        
        return $aspectDef;
    }
    
    private function _setupMetaDef(array &$parent, $parentDef)
    {
        if(isset($parent['meta'])){
            foreach ($parent['meta'] as $val) {
                $name = $this->getName($val);
                $metaDef = new S2Container_MetaDefImpl($name);
                $this->_setupArgDefInternal((array)$val, $metaDef);
                $parentDef->addMetaDef($metaDef);
            }
            if($parent['meta']){
                unset($parent['meta']);
            }
        }
    }
    
    private function _getInjectionValue($value){
        if (preg_match(self::regex_php_value, $value, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    private function getName(array $array){
        if(isset($array['name'])){
            return $array['name'];
        }
        return '';
    }
}
?>