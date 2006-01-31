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
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class S2Container_XmlS2ContainerBuilder
    implements S2ContainerBuilder
{
    private $container = null;
 
    /**
     * 
     */
    public function includeChild(S2Container $parent, $path) 
    {
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }
         
    /**
     * 
     */
    public function build($path)
    {
        $root = $this->_loadDicon($path);

        $container = new S2ContainerImpl();
        $container->setPath($path);
    
        $namespace = trim((string)$root['namespace']);
        if ($namespace != "") {
            $container->setNamespace($namespace); 
        }

        $this->_setupInclude($container,$root);

        $this->container = $container;
        S2Container_ChildComponentDefBindingUtil::init();
        foreach ($root->component as $index => $val) {
            $this->_setupComponentDef($val);               
        }
        $this->_setupMetaDef($root,$this->container);
        S2Container_ChildComponentDefBindingUtil::bind($this->container);

        return $this->container;
    }
    
    /**
     * 
     */
    private function _loadDicon($path){
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001',array($path));
        }
        
        if (defined('S2CONTAINER_PHP5_DOM_VALIDATE') and 
            S2CONTAINER_PHP5_DOM_VALIDATE) {
            $dom = new DomDocument();
            $dom->validateOnParse = true;
            $dom->load($path);
            if (!$dom->validate()) {
                throw new S2Container_S2RuntimeException('ESSR1001',array($path));
            }
            $root = simplexml_import_dom($dom);
        } else {
            $root = simplexml_load_file($path);
        }

        return $root;        
    }

    /**
     * 
     */
    private function _setupInclude(S2Container $container,$root){
        foreach ($root->include as $index => $val) {
            $path = trim((string)$val['path']);
            $path = S2Container_StringUtil::expandPath($path);
            
            if (!is_readable($path)) {
                throw new S2Container_S2RuntimeException('ESSR0001',array($path));
            }
            $child = S2ContainerFactory::includeChild($container,$path);
            $child->setRoot($container->getRoot());
        }
    }

    /**
     * 
     */
    private function _createComponentDef($component,$className)
    {
        $name = trim((string)$component['name']);
        $instanceMode = trim((string)$component['instance']);
        $autoBindingMode = trim((string)$component['autoBinding']);
        $compExp = trim((string)$component);
        
        $componentDef = new S2Container_ComponentDefImpl($className,$name);

        if ($compExp != "") {
            $componentDef->setExpression($compExp);            
        }   

        if ($instanceMode != "") {
            $componentDef->setInstanceMode($instanceMode);
        }

        if ($autoBindingMode != "") {
            $componentDef->setAutoBindingMode($autoBindingMode);
        }
           
        return $componentDef;
    }
    
    /**
     * 
     */
    private function _setupComponentDef($component)
    {
        $className = trim((string)$component['class']);
        $componentDef = $this->_createComponentDef($component,$className);
        $this->container->register($componentDef );

        foreach ($component->arg as $index => $val) {
            $componentDef->addArgDef($this->_setupArgDef($val));               
        }

        foreach ($component->property as $index => $val) {
            $componentDef->addPropertyDef($this->_setupPropertyDef($val));               
        }

        foreach ($component->initMethod as $index => $val) {
            $componentDef->
                addInitMethodDef($this->_setupInitMethodDef($val));               
        }

        foreach ($component->destroyMethod as $index => $val) {
            $componentDef->
                  addDestroyMethodDef($this->_setupDestroyMethodDef($val));               
        }

        foreach ($component->aspect as $index => $val) {
            $componentDef->addAspectDef($this->_setupAspectDef($val,
                                                              $className));               
        }
           
        $this->_setupMetaDef($component,$componentDef);

        return $componentDef;
    }    
    
    /**
     * 
     */
    private function _setupArgDef($arg)
    {
        $argDef = new S2Container_ArgDefImpl();
        $this->_setupArgDefInternal($arg,$argDef);
        $this->_setupMetaDef($arg,$argDef);
        
        return $argDef;
    }

    /**
     * @param SimpleXMLElement arg | property | meta
     * @param S2Container_ArgDef
     */
    private function _setupArgDefInternal($arg,S2Container_ArgDef $argDef)
    {
        if (count($arg->component[0]) == null) {
            $argValue = trim((string)$arg);
            $injectValue = $this->_getInjectionValue($argValue);
            if ($injectValue != null){
                $argDef->setValue($injectValue);
            }else{
                $argDef->setExpression($argValue);
                S2Container_ChildComponentDefBindingUtil::put($argValue,$argDef);
            }
        } else {
            $childComponent = $this->_setupComponentDef($arg->component[0]);
            $argDef->setChildComponentDef($childComponent);
        }
    }


    /**
     * 
     */
    private function _setupPropertyDef($property)
    {
        $name = (string)$property['name'];
        $propertyDef = new S2Container_PropertyDefImpl($name);
        $this->_setupArgDefInternal($property,$propertyDef);
        $this->_setupMetaDef($property,$propertyDef);
        
        return $propertyDef;
    }
    
    /**
     * 
     */
    private function _setupInitMethodDef($initMethod)
    {
        $name = (string)$initMethod['name'];
        $initMethodDef = new S2Container_InitMethodDefImpl($name);
        $this->_setupMethodDefInternal($initMethod,$initMethodDef);
        return $initMethodDef;
    }

    /**
     * 
     */
    private function _setupDestroyMethodDef($destroyMethod)
    {
        $name = (string)$destroyMethod['name'];
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);
        $this->_setupMethodDefInternal($destroyMethod,$destroyMethodDef);
        return $destroyMethodDef;
    }

    /**
     * @param SimpleXMLElement initMethod | destroyMethod element
     * @param S2Container_MethodDef
     */
    private function _setupMethodDefInternal($method,S2Container_MethodDef $methodDef)
    {
        $exp = trim((string)$method);
        if ($exp != "") {
            $methodDef->setExpression($exp);
        }

        foreach ($method->arg as $index => $val) {
            $methodDef->addArgDef($this->_setupArgDef($val));               
        }
    }

    /**
     * 
     */
    private function _setupAspectDef($aspect,$targetClassName)
    {
        $pointcut = trim((string)$aspect['pointcut']);

        if ($pointcut == "") {
            $pointcut = new S2Container_PointcutImpl($targetClassName);
        } else {
            $pointcuts = split(",",$pointcut);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        }
        
        $aspectDef = new S2Container_AspectDefImpl($pointcut);
        if (count($aspect->component[0]) == null) {
            $aspectValue = trim((string)$aspect);
            $injectValue = $this->_getInjectionValue($aspectValue);
            if ($injectValue != null){
                 $aspectValue = $injectValue;
            }
            $aspectDef->setExpression($aspectValue);
            S2Container_ChildComponentDefBindingUtil::put($aspectValue,$aspectDef);
        } else {
            $childComponent = $this->_setupComponentDef($aspect->component[0]);
            $aspectDef->setChildComponentDef($childComponent);
        }
        
        return $aspectDef;
    }
    
    /**
     * 
     */
    private function _setupMetaDef($parent,$parentDef)
    {
        foreach ($parent->meta as $index => $val) {
            $name = trim((string)$val['name']);
            $metaDef = new S2Container_MetaDefImpl($name);
            $this->_setupArgDefInternal($val,$metaDef);
            $parentDef->addMetaDef($metaDef);
        }
    }
    
    /**
     * 
     */
    private function _getInjectionValue($value){
        if (preg_match("/^\"(.*)\"$/",$value,$matches) or
            preg_match("/^\'(.*)\'$/",$value,$matches)) {
            return $matches[1];
        }
        return null;
    }
}
?>
