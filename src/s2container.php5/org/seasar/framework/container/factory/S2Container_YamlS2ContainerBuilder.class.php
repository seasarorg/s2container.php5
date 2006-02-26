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
        $root = $this->_loadDicon($path);

        $container = new S2ContainerImpl();
        $container->setPath($path);

        $this->_setupNameSpace($container, $root);
        $this->_setupInclude($container, $root);

        $this->container = $container;
        S2Container_ChildComponentDefBindingUtil::init();
        
        $root = (array)$root;
        $this->_setupMetaDef($root, $this->container);
        foreach($root as $className => $val){
            $this->_setupComponentDef($className, $val);
        }
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
    
    private function _setupNameSpace(S2Container $container, $root){
        if (isset($root['namespace'])) {
            $container->setNamespace($root['namespace']); 
        }
        unset($root['namespace']);
    }

    private function _setupInclude(S2Container $container, $root){
        if(isset($root['include'])){
            $include = array();
            if(!is_array($root['include'])){
                $include = (array)$root['include'];
            } else {
                $include = $root['include'];
            }
            
            foreach($include as $index => $val){
                $path = S2Container_StringUtil::expandPath($path);
                if (!is_readable($path)) {
                    throw new S2Container_S2RuntimeException('ESSR0001',array($path));
                }
                $child = S2ContainerFactory::includeChild($container,$path);
                $child->setRoot($container->getRoot());
            }
        }
        unset($root['include']);
    }

    private function _setupComponentDef($className, array $component)
    {
        $componentDef = $this->_createComponentDef($className, $component);
        $this->container->register($componentDef);

        $arg = (array)$component['arg'];
        foreach ($arg as $val) {
            $componentDef->addArgDef($this->_setupArgDef($val));               
        }
        
        $property = (array)$component['property'];
        foreach ($property as $val) {
            $componentDef->addPropertyDef($this->_setupPropertyDef($val));               
        }

        $initMethod = (array)$component['initMethod'];
        foreach ($initMethod as $val) {
            $componentDef->addInitMethodDef($this->_setupInitMethodDef($val));
        }

        $destroyMethod = (array)$component['destroyMethod'];
        foreach ($destroyMethod as $val) {
                $componentDef->addDestroyMethodDef($this->_setupDestroyMethodDef($val));
        }
        
        $aspect = (array)$component['aspect'];
        foreach ($aspect as $val) {
            $componentDef->addAspectDef($this->_setupAspectDef($val, $className));
        }
        $this->_setupMetaDef($component, $componentDef);

        return $componentDef;
    }

    private function _createComponentDef($className, array $component)
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
    
    private function _setupArgDef($arg)
    {
        $argDef = new S2Container_ArgDefImpl();
        $this->_setupArgDefInternal($arg, $argDef);
        $this->_setupMetaDef($arg, $argDef);
        return $argDef;
    }

    private function _setupArgDefInternal($arg, S2Container_ArgDef $argDef)
    {
        if(!isset($arg['component'])){
            $argValue = $arg[0];
            $injectValue = $this->_getInjectionValue($argValue);
            if ($injectValue != null){
                $argDef->setValue($injectValue);
            }else{
                $argDef->setExpression($argValue);
                S2Container_ChildComponentDefBindingUtil::put($argValue, $argDef);
            }
        } else {
            $childComponent = $this->_setupComponentDef($arg['component']);
            $argDef->setChildComponentDef($childComponent);
        }
    }

    private function _setupPropertyDef(array $property)
    {
        $name = $this->getName($property);
        $propertyDef = new S2Container_PropertyDefImpl($name);
        $this->_setupArgDefInternal($property, $propertyDef);
        $this->_setupMetaDef($property, $propertyDef);
        return $propertyDef;
    }
    
    private function _setupInitMethodDef(array $initMethod)
    {
        $name = $this->getName($initMethod);
        $initMethodDef = new S2Container_InitMethodDefImpl($name);
        $this->_setupMethodDefInternal($initMethod, $initMethodDef);
        return $initMethodDef;
    }

    private function _setupDestroyMethodDef(array $destroyMethod)
    {
        $name = $this->getName($destroyMethod);
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);
        $this->_setupMethodDefInternal($destroyMethod, $destroyMethodDef);
        return $destroyMethodDef;
    }

    private function _setupMethodDefInternal($method, S2Container_MethodDef $methodDef)
    {
        if (isset($method[0]) && is_string($method[0])) {
            $initMethodDef->setExpression($method[0]);
        }

        $arg = (array)$method['arg'];
        foreach ($arg as $val) {
            $initMethodDef->addArgDef($this->_setupArgDef($val));
        }
    }

    private function _setupAspectDef($aspect, $targetClassName)
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
    
    private function _setupMetaDef(array $parent, $parentDef)
    {
        $meta = (array)$parent['meta'];
        foreach ($meta as $val) {
            $name = $this->getName($val);
            $metaDef = new S2Container_MetaDefImpl($name);
            $this->_setupArgDefInternal($val, $metaDef);
            $parentDef->addMetaDef($metaDef);
        }
        if($parent['meta']){
            unset($parent['meta']);
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