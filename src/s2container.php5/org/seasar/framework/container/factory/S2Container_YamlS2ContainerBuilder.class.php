<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the 'License');      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an 'AS IS' BASIS,    |
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
 */
final class S2Container_YamlS2ContainerBuilder
    implements S2ContainerBuilder
{

    // {{{ properties
    /**
     *
     */
    private $container = null;
    /**
     *
     */
    private $spyc = null;
    // }}}

    // {{{ constructor
    /**
     * require Spyc
     */
    public function __construct()
    {
        if(!class_exists('Spyc')){
            throw new S2Container_S2RuntimeException('ESSR0017', array('required Spyc'));
        }

        $this->spyc = new Spyc();
    }
    // }}}

    // {{{ includeChild
    /**
     *
     */
    public function includeChild(S2Container $parent, $path)
    {
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }
    // }}}

    // {{{ build
    /**
     *
     */
    public function build($path)
    {
        $container = new S2ContainerImpl();
        $container->setPath($path);

        $root = $this->_loadDicon($path);
        $this->_setupNameSpace($container, $root);
        $this->_setupInclude($container, $root);

        $this->container = $container;
        S2Container_ChildComponentDefBindingUtil::init();

        foreach($root as &$component){
            if(!is_array($component)){
                continue;
            }
            if(isset($component['component'])){
                $this->_setupComponentDef($component);
            }
        }
        $this->_setupMetaDef($root, $this->container);
        S2Container_ChildComponentDefBindingUtil::bind($this->container);
        return $this->container;
    }
    // }}}

    // {{{ _loadDicon
    /**
     *
     */
    private function _loadDicon($path){
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001', array($path));
        }

        $yaml = $this->spyc->load($path);
        if(isset($yaml['components'])){
            return $yaml['components'];
        }
        return $yaml[0];
    }
    // }}}

    // {{{ _setupNameSpace
    /**
     *
     */
    private function _setupNameSpace(S2Container $container, &$root){
        if (!isset($root['namespace'])) {
            return;
        }
        $container->setNamespace($root['namespace']);
    }
    // }}}

    // {{{ _setupInclude
    /**
     *
     */
    private function _setupInclude(S2Container $container, &$root){
        if(!isset($root['include'])){
            return;
        }
        foreach($root['include'] as $path){
            $path = S2Container_StringUtil::expandPath($path);
            if (!is_readable($path)) {
                throw new S2Container_S2RuntimeException('ESSR0001', array($path));
            }
            $child = S2ContainerFactory::includeChild($container, $path);
            $child->setRoot($container->getRoot());
        }
    }
    // }}}

    // {{{ _setupComponentDef
    /**
     *
     */
    private function _setupComponentDef(array &$component)
    {
        $className = $this->_getClass($component);
        $componentDef = $this->_createComponentDef($component, $className);
        $this->container->register($componentDef);
        foreach($component as &$comp){
            if(!is_array($comp)){
                continue;
            }
            $this->_setupDefs($componentDef, $comp, $className);
        }
        return $componentDef;
    }
    // }}}

    // {{{ _setupDefs
    /**
     *
     */
    private function _setupDefs($componentDef, array &$component, $className)
    {
        $name = $this->_getName($component);
        if(isset($component['arg']) || isset($component['php'])){
            $componentDef->addArgDef($this->_setupArgDef($component));
        }
        
        if(isset($component['property'])){
            $property = $component['property'];
            $propertyDef = $this->_setupPropertyDef($component, $name);
            if($property != '.'){
                $propertyDef->setExpression($property);
            }
            $componentDef->addPropertyDef($propertyDef);
        }
        
        if(isset($component['initMethod'])){
            $initMethod = $component['initMethod'];
            $initMethodDef = $this->_setupInitMethodDef($component, $name);
            if($initMethod != '.'){
                $initMethodDef->setExpression($initMethod);
            }
            $componentDef->addInitMethodDef($initMethodDef);
        }

        if(isset($component['destroyMethod'])){
            $destroyMethod = $component['destroyMethod'];
            $destroyMethodDef = $this->_setupDestroyMethodDef($component, $name);
            if($destroyMethod != '.'){
                $destroyMethodDef->setExpression($destroyMethod);
            }
            $componentDef->addDestroyMethodDef($destroyMethodDef);
        }

        if(isset($component['aspect'])){
            $componentDef->addAspectDef($this->_setupAspectDef($component, $className));
        }
        
        if(isset($component['interType'])){
            $componentDef->addInterTypeDef($this->_setupInterTypeDef($component, $className));
        }

        $this->_setupMetaDef($component, $componentDef);

        if(is_array($component)){
            foreach($component as &$comp){
                if(!is_array($comp)){
                    continue;
                }
                $this->_setupDefs($componentDef, $comp, $className);
            }
        }
    }
    // }}}

    // {{{ _createComponentDef
    /**
     *
     */
    private function _createComponentDef(array &$component, $className)
    {
        $exp = '';
        $name = $this->_getName($component);
        $componentDef = new S2Container_ComponentDefImpl($className, $name);
        if($component['component'] != '.'){
            $exp = $component['component'];
        }
        $componentDef->setExpression($exp);

        if (isset($component['instance'])) {
            $componentDef->setInstanceMode($component['instance']);
        }

        if (isset($component['autoBinding'])) {
            $componentDef->setAutoBindingMode($component['autoBinding']);
        }

        return $componentDef;
    }
    // }}}

    // {{{ _setupArgDef
    /**
     *
     */
    private function _setupArgDef(array &$arg)
    {
        $argDef = new S2Container_ArgDefImpl();
        $this->_setupArgDefInternal($arg, $argDef);
        $this->_setupMetaDef($arg, $argDef);
        return $argDef;
    }
    // }}}

    // {{{ _setupArgDefInternal
    /**
     *
     */
    private function _setupArgDefInternal(array &$arg, S2Container_ArgDef $argDef)
    {
        if(isset($arg['arg'], $arg['php'])){
            $_arg = $arg['arg'];
            $_php = $arg['php'];
            if(!is_array($_arg) && $_arg == '.'){
                $_array = array('php' => $_php);
                return $this->_setupArgDefInternal($_array, $argDef);
            }
        }
        if(isset($arg['php'])){
            $php = $arg['php'];
            if(is_array($php)){
                return $this->_setupArgDefEach('php', $php, $argDef);
            }
            $argDef->setExpression($php);
            S2Container_ChildComponentDefBindingUtil::put($php, $argDef);
            return;
        } else if($this->__issetValue('arg', $arg)){
            $args = $arg['arg'];
            if(is_array($args)){
                return $this->_setupArgDefEach('arg', $args, $argDef);
            }
            $argDef->setValue($args);
            return;
        }

        if(isset($arg['component'])){
            $childComponent = $this->_setupComponentDef($arg);
            $argDef->setChildComponentDef($childComponent);
            return;
        }

        foreach($arg as $_arg){
            if(!is_array($_arg)){
                continue;
            }
            return $this->_setupArgDefInternal($_arg, $argDef);
        }
    }
    // }}}
    
    // {{{ _setupArgDefEach
    /**
     *
     */
    private function _setupArgDefEach($key, array &$arg, S2Container_ArgDef $argDef)
    {
        foreach($arg as &$_arg){
            if(!is_array($_arg)){
                $_arg = array($key => $_arg);
            }
            return $this->_setupArgDefInternal($_arg, $argDef);
        }
    }
    // }}}
    
    // {{{ _setupPropertyDef
    /**
     *
     */
    private function _setupPropertyDef(array &$property, $name)
    {
        $propertyDef = new S2Container_PropertyDefImpl($name);
        $this->_setupArgDefInternal($property, $propertyDef);
        $this->_setupMetaDef($property, $propertyDef);
        return $propertyDef;
    }
    // }}}

    // {{{ _setupInitMethodDef
    /**
     *
     */
    private function _setupInitMethodDef(array $initMethod, $name)
    {
        $initMethodDef = new S2Container_InitMethodDefImpl($name);
        $this->_setupMethodDefInternal($initMethod, $initMethodDef);
        return $initMethodDef;
    }
    // }}}

    // {{{ _setupDestroyMethodDef
    /**
     *
     */
    private function _setupDestroyMethodDef(array $destroyMethod, $name)
    {
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);
        $this->_setupMethodDefInternal($destroyMethod, $destroyMethodDef);
        return $destroyMethodDef;
    }
    // }}}

    // {{{ _setupMethodDefInternal
    /**
     *
     */
    private function _setupMethodDefInternal(array $method, S2Container_MethodDef $methodDef)
    {
        foreach($method as $_method){
            if(!is_array($_method)){
                continue;
            }
            $methodDef->addArgDef($this->_setupArgDef($_method));
        }
    }
    // }}}

    // {{{ _setupAspectDef
    /**
     *
     */
    private function _setupAspectDef(array $aspect, $targetClassName)
    {
        if (isset($aspect['pointcut'])) {
            $pointcuts = explode(',', $aspect['pointcut']);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        } else {
            $pointcut = new S2Container_PointcutImpl($targetClassName);
        }

        $aspectDef = new S2Container_AspectDefImpl($pointcut);
        if(isset($aspect['component'])){
            $childComponent = $this->_setupComponentDef($aspect);
            $aspectDef->setChildComponentDef($childComponent);
            return $aspectDef;
        }

        $aspectValue = null;
        if($this->__issetArray('aspect', $aspect)){
            if($this->__issetValue('aspect', $aspect)){
                $aspectValue = $aspect['aspect'];
            } else {
                $aspectValue = $aspect;
            }
        }
        if(is_array($aspectValue)){
            if($this->__issetValue('arg', $aspectValue)){
                $aspectValue = $aspectValue['arg'];
            }
            if($this->__issetValue('php', $aspectValue)){
                $aspectValue = $aspectValue['php'];
            }
        }
        if($aspectValue !== null){
            $aspectDef->setExpression($aspectValue);
            S2Container_ChildComponentDefBindingUtil::put($aspectValue, $aspectDef);
        }

        return $aspectDef;
    }
    // }}}
    
    // {{{ _setupInterTypeDef
    /**
     * 
     */
    private function _setupInterTypeDef(array $interType, $targetClassName){
        $interTypeValue = $interType['interType'];
        $interTypeDef = new S2Container_InterTypeDefImpl($targetClassName);
        $interTypeDef->setValue($interTypeValue);
        S2Container_ChildComponentDefBindingUtil::put($interTypeValue, $interTypeDef);
        return $interTypeDef;
    }
    // }}}

    // {{{ _setupMetaDef
    /**
     *
     */
    private function _setupMetaDef(array &$parent, $parentDef)
    {
        foreach($parent as &$component){
            if(!is_array($component)){
                continue;
            }
            if(!isset($component['meta'])){
                continue;
            }
            $name = $this->_getName($component);
            $metaDef = new S2Container_MetaDefImpl($name);
            $this->_setupArgDefInternal($component, $metaDef);
            $parentDef->addMetaDef($metaDef);
        }
    }
    // }}}

    // {{{ _getName
    /**
     *
     */
    private function _getName(array $array){
        if(isset($array['name'])){
            return $array['name'];
        }
        return '';
    }
    // }}}

    // {{{ _getClass
    /**
     *
     */
    private function _getClass(array $array){
        if(isset($array[0]) && is_array($array[0])){
            if(isset($array[0]['class'])){
                return $array[0]['class'];
            }
        }
        return '';
    }
    // }}}

    // {{{ __issetValue
    /**
     *
     */
    private function __issetValue($key, array $target){
        return isset($target[$key]) && $target[$key] != '.';
    }
    // }}}

    // {{{ __issetArray
    /**
     *
     */
    private function __issetArray($key, $target){
        return is_array($target) && isset($target[$key]);
    }
    // }}}
    
}
?>
