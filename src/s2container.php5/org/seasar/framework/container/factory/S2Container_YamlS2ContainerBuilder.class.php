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
    private $unresolvedCompRef_ = array();
    private $yetRegisteredCompRef_ = array();
    private $spyc = null;
    // }}}

    // {{{ constructor
    /**
     * 
     */
    public function __construct()
    {
        $this->spyc = new Spyc();
    }
    // }}}

    /**
     * 
     */
    public function build($path, $classLoader = null)
    {
        if(!is_readable($path)){
            throw new S2Container_S2RuntimeException('ESSR0001',array($path));
        }

        $yaml = $this->spyc->load($path);
        if(isset($yaml['components'])){
            $root = $yaml['components'];
        } else {
            $root = $yaml[0];
        }

        $container = new S2ContainerImpl();
        $container->setPath($path);
        if (isset($root['namespace'])) {
            $container->setNamespace($root['namespace']); 
        }
        unset($root['namespace']);

        if(isset($root['include'])){
            $includepath = $root['include'];
            if(!is_readable($includepath)){
                throw new S2Container_S2RuntimeException('ESSR0001', array($includepath));
            }
            $child = S2ContainerFactory::includeChild($container, $includepath);
            $child->setRoot($container->getRoot());
        }
        unset($root['include']);

        foreach($root as $componentClass => $value){
            if(isset($value[0])){
                foreach($value as $cmpClass => $val){
                    $container->register($this->_setupComponentDef($cmpClass, $val));
                }
            } else {
                $container->register($this->_setupComponentDef($componentClass, $value));
            }
        }

        $this->_setupMetaDef($root, $container);

        foreach ($this->yetRegisteredCompRef_ as $compRef) {
            $container->register($compRef);
        }
        $this->yetRegisteredCompRef_ = array();
           
        if (0 < count(array_keys($this->unresolvedCompRef_))) {
            foreach ($this->unresolvedCompRef_ as $key => $val) {
                foreach ($val as $argDef) {
                    if ($container->hasComponentDef($key)) {
                        $argDef->setChildComponentDef($container->getComponentDef($key));
                        $argDef->setExpression("");
                    }
                }
            }
        }
        $this->unresolvedCompRef_ = array();
        return $container;
    }

    /**
     * 
     */
    private function _setupComponentDef($className, array $component)
    {
        $name = isset($component['name']) ? $component['name'] : "";
        $componentDef = new S2Container_ComponentDefImpl($className, $name);
        $componentDef->setExpression("");

        if (isset($component['instance'])) {
            $componentDef->setInstanceMode($component['instance']);
        }

        if (isset($component['autoBinding'])) {
            $componentDef->setAutoBindingMode($component['autoBinding']);
        }

        $arg = array();
        if(isset($component['arg'])){
            $arg[] = $this->array_value($component, 'arg');
        }
        foreach ($arg as $val) {
            $componentDef->addArgDef($this->_setupArgDef($val));
        }

        $property = array();
        if(isset($component['property'])){
            $property[] = $this->array_value($component, 'property');
        }
        foreach ($property as $val) {
            // FIXME no.1
            if(isset($val[0]) && is_array($val[0])){
                foreach($val as $v){
                    $componentDef->addPropertyDef($this->_setupPropertyDef($v));
                }
            } else {
                $componentDef->addPropertyDef($this->_setupPropertyDef($val));
            }
        }

        $initMethod = array();
        if(isset($component['initMethod'])){
            $initMethod[] = $this->array_value($component, 'initMethod');
        }
        foreach ($initMethod as $val) {
            // FIXME no.2
            if(isset($val[0]) && is_array($val[0])){
                foreach($val as $v){
                    $componentDef->addInitMethodDef($this->_setupInitMethodDef($v));
                }
            } else {
                $componentDef->addInitMethodDef($this->_setupInitMethodDef($val));
            }
        }

        $destroyMethod = array();
        if(isset($component['destroyMethod'])){
            $destroyMethod[] = $this->array_value($component, "destroyMethod");
        }
        foreach ($destroyMethod as $val) {
            // FIXME no.3
            if(isset($val[0]) && is_array($val[0])){
                foreach($val as $v){
                    $componentDef->addDestroyMethodDef($this->_setupDestroyMethodDef($v));
                }
            } else {
                $componentDef->addDestroyMethodDef($this->_setupDestroyMethodDef($val));
            }
        }

        $aspect = array();
        if(isset($component['aspect'])){
            $aspect[] = $this->array_value($component, 'aspect');
        }
        foreach ($aspect as $val) {
            // FIXME no.4
            if(isset($val[0]) && is_array($val[0])){
                foreach($val as $v){
                    $componentDef->addAspectDef($this->_setupAspectDef($v, $className));
                }
            } else {
                $componentDef->addAspectDef($this->_setupAspectDef($val, $className));
            }
        }
        $this->_setupMetaDef($component, $componentDef);

        return $componentDef;
    }

    /**
     * 
     */
    private function _setupArgDef(array $arg)
    {
        $argDef = new S2Container_ArgDefImpl();

        if(!isset($arg['component'])){
            $argValue = $arg[0];
            if(preg_match(self::regex_php_value, $argValue, $regs)){
                $value = $regs[1];
                $argDef->setExpression($value);
                if (array_key_exists($value, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$value], $argDef);
                } else {
                    $this->unresolvedCompRef_[$value] = array($argDef);
                }
            } else {
                $argDef->setValue($argValue);
            }
        } else {
            $childComponent = $this->_setupComponentDef($arg['component']);
            $argDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }
        $this->_setupMetaDef($arg, $argDef);
        
        return $argDef;
    }

    /**
     * 
     */
    private function _setupPropertyDef(array $property)
    {
        $name = "";
        if(isset($property['name'])){
            $name = $property['name'];
        }
        $propertyDef = new S2Container_PropertyDefImpl($name);

        if (!isset($property['component'])) {
            $propertyValue = $property[0];
            if(preg_match(self::regex_php_value, $propertyValue, $regs)){
                $value = $regs[1];
                $propertyDef->setExpression($value);
                if (array_key_exists($value, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$value], $propertyDef);
                } else {
                    $this->unresolvedCompRef_[$value] = array($propertyDef);
                }
            } else {
                $propertyDef->setValue($propertyValue);
            }
        } else {
            $childComponent = $this->_setupComponentDef($property['component']);
            $propertyDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }

        $this->_setupMetaDef($property, $propertyDef);
        
        return $propertyDef;
    }
    
    /**
     * 
     */
    private function _setupInitMethodDef(array $initMethod)
    {
        $name = "";
        if(isset($initMethod['name'])){
            $name = $initMethod['name'];
        }
        $initMethodDef = new S2Container_InitMethodDefImpl($name);

        if (isset($initMethod[0]) && is_string($initMethod[0])) {
            $initMethodDef->setExpression($initMethod[0]);
        }

        $arg = array();
        if(isset($initMethod['arg'])){
            $arg[] = $this->array_value($initMethod, 'arg');
        }
        foreach ($arg as $val) {
            $initMethodDef->addArgDef($this->_setupArgDef($val));
        }

        return $initMethodDef;
    }

    /**
     * 
     */
    private function _setupDestroyMethodDef(array $destroyMethod)
    {
        $name = "";
        if(isset($destroyMethod['name'])){
            $name = $destroyMethod['name'];
        }
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);

        if (isset($destroyMethod[0])) {
            $destroyMethodDef->setExpression($destroyMethod[0]);
        }

        $arg = array();
        if(isset($destroyMethod['arg'])){
            $arg = $this->array_value($destroyMethod, 'arg');
        }
        foreach ($arg as $val) {
            $destroyMethodDef->addArgDef($this->_setupArgDef($val));
        }

        return $destroyMethodDef;
    }

    /**
     * 
     */
    private function _setupAspectDef(array $aspect, $targetClassName)
    {
        if (isset($aspect['pointcut'])) {
            $pointcuts = split(",", $aspect['pointcut']);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        } else {
            $pointcut = new S2Container_PointcutImpl($targetClassName);
        }
        
        $aspectDef = new S2Container_AspectDefImpl($pointcut);
        if (!isset($aspect['component'])) {
            $aspectValue = $aspect[0];
            if(preg_match(self::regex_php_value, $aspectValue, $regs)){
                $value = $regs[1];
                $aspectDef->setExpression($value);
                if (array_key_exists($value, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$value], $aspectDef);
                } else {
                    $this->unresolvedCompRef_[$value] = array($aspectDef);
                }
            } else {
                $aspectDef->setValue($aspectValue);
            }
        } else {
            $childComponent = $this->_setupComponentDef($aspect['component']);
            $aspectDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }
        
        return $aspectDef;
    }
    
    /**
     * 
     */
    private function _setupMetaDef(array $parent, $parentDef)
    {
        $meta = array();
        if(isset($parent['meta'])){
            $meta = $parent['meta'];
        }

        foreach ($meta as $val) {
            $name = "";
            if(isset($val['name'])){
                $name = $val['name'];
            }
            $metaDef = new S2Container_MetaDefImpl($name);

            if (!isset($val['component'])) {
                $metaValue = $val[0];
                if(preg_match(self::regex_php_value, $metaValue, $regs)){
                    $value = $regs[1];
                    $metaDef->setExpression($value);
                    if (array_key_exists($value, $this->unresolvedCompRef_)) {
                        array_push($this->unresolvedCompRef_[$value], $metaDef);
                    } else {
                        $this->unresolvedCompRef_[$value] = array($metaDef);
                    }
                } else {
                    $metaDef->setValue($metaValue);
                }
            } else {
                $childComponent = $this->_setupComponentDef($val['component']);
                $metaDef->setChildComponentDef($childComponent);
                array_push($this->yetRegisteredCompRef_, $childComponent);
            }
            $parentDef->addMetaDef($metaDef);
        }
    }

    
    /**
     * 
     */
    public function includeChild(S2Container $parent, $path) 
    {
        $child = null;
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }

    private function array_value(array $array, $key){
        if(isset($array[$key]) && isset($array[$key])){
            return $array[$key];
        } else {
            return array($array);
        }
    }
}
?>
