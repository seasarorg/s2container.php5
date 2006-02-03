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
 * Yaml Document Sample
 * ----- Sample Yaml (foo.dicon.yml)----
 *components:
 *    namespace: foonamespace
 *    include: foopath
 *    meta:
 *        name: foo-components-meta
 *        - foo components meta values in
 *    fooClass:
 *        name: fooComponent
 *        arg:
 *            - foo arg values in
 *        property: 
 *            name: fooPropertyName
 *            - foo property values in
 *        initMethod:
 *            name: fooInitmethodName
 *            - foo initmethod values in
 *        destroyMethod:
 *            name: fooDestroymethodName
 *            - foo destroymethod values in
 *        aspect: foo aspect value
 * ----- AS XML (foo.dicon.xml)-----
 * <components namespace="foonamespace">
 *      <include path="foopath" />
 *      <meta name="foo-components-meta">
 *          "foo components meta values in"
 *      </meta>
 *      <component name="fooComponent" class="fooClass">
 *          <arg name="fooArgName">
 *              "foo arg values in"
 *          </arg>
 *          <property name="fooPropertyName">
 *              "foo property values in"
 *          </property>
 *          <initMethod name="fooInitmethodName">
 *              "foo initmethod values in"
 *          </initMethod>
 *          <destroyMethod name="fooDestroymethodName">
 *              "foo destroymethod values in"
 *          </destroy>
 *          <aspect>"foo aspect value"</aspect>
 *      </component>
 *</components>

/**
 * @package org.seasar.framework.container.factory
 * @author nowel
 * @version test...orz
 */
final class S2Container_YamlS2ContainerBuilder
    implements S2ContainerBuilder
{

    // {{{ properties
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
        if(isset($yaml["components"])){
            $root = $yaml["components"];
        } else {
            $root = $yaml[0];
        }

        $container = new S2ContainerImpl();
        $container->setPath($path);
        if (isset($root["namespace"])) {
            $container->setNamespace($root["namespace"]); 
        }
        unset($root["namespace"]);

        if(isset($root["include"])){
            $includepath = $root["include"];
            if(!is_readable($includepath)){
                throw new S2Container_S2RuntimeException('ESSR0001', array($includepath));
            }
            $child = S2ContainerFactory::includeChild($container, $includepath);
            $child->setRoot($container->getRoot());
        }
        unset($root["include"]);

        foreach($root as $component => $value){
            $container->register($this->_setupComponentDef($value));
        }

        var_dump(__CLASS__, __LINE__);
        die;

        // FIXME, TODO...
        $component = array();
        if(isset($root["component"])){
            $component[] = $this->array_append($root["component"]);
        }
        foreach($component as $value){
            $container->register($this->_setupComponentDef($value));
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
    private function _setupComponentDef(array $component)
    {
        $className = isset($component['class']) ? $component['class'] : null;
        $name = isset($component['name']) ? $component['name'] : null;
        
        $componentDef = new S2Container_ComponentDefImpl($className, $name);

        $compExp = (string)$component;
        if ($compExp != "") {
            $componentDef->setExpression($compExp);
        } 

        if (isset($component['instance'])) {
            $componentDef->setInstanceMode($component['instance']);
        }

        if (isset($component['autoBinding'])) {
            $componentDef->setAutoBindingMode($component['autoBinding']);
        }

        // FIXME
        // array_append ....orz
        $arg = array();
        if(isset($component["arg"])){
            $arg[] = $this->array_append($component["arg"]);
        }
        foreach ($arg as $val) {
            $componentDef->addArgDef($this->_setupArgDef($val, $component));
        }

        $property = array();
        if(isset($component["property"])){
            $property[] = array_append($component["property"]);
        }
        foreach ($property as $val) {
            $componentDef->addPropertyDef($this->_setupPropertyDef($val, $component));
        }

        $initMethod = array();
        if(isset($component["initMethod"])){
            $initMethod[] = array_append($component["initMethod"]);
        }
        foreach ($initMethod as $val) {
            $componentDef->addInitMethodDef($this->_setupInitMethodDef($val));
        }

        $destroyMethod = array();
        if(isset($component["destroyMethod"])){
            $destroyMethod[] = array_append($component["destroyMethod"]);
        }
        foreach ($destroyMethod as $index => $val) {
            $componentDef->addDestroyMethodDef($this->_setupDestroyMethodDef($val));
        }

        $aspect = array();
        if(isset($component["aspect"])){
            $aspect[] = array_append($component["aspect"]);
        }
        foreach ($aspect as $val) {
            $componentDef->addAspectDef($this->_setupAspectDef($val, $className, $component));
        }
        
        $this->_setupMetaDef($component, $componentDef);

        return $componentDef;
    }

    /**
     * 
     */
    private function _setupArgDef(array $arg, $component)
    {
        $argDef = new S2Container_ArgDefImpl();

        if($component == null){
            $argValue = $arg[0];
            if (preg_match("/^\"(.+)\"$/",$argValue, $regs) or preg_match("/^\'(.+)\'$/",$argValue, $regs)) {
                $argDef->setValue($regs[1]);
            } else {
                $argDef->setExpression($argValue);
                if (array_key_exists($arg, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$arg], $argDef);
                } else {
                    $this->unresolvedCompRef_[$arg] = array($argDef);
                }
            }
        } else {
            $childComponent = $this->_setupComponentDef($component);
            $argDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }

        $this->_setupMetaDef($arg, $argDef);
        
        return $argDef;
    }

    /**
     * 
     */
    private function _setupPropertyDef(array $property, $component)
    {
        $propertyDef = new S2Container_PropertyDefImpl($property['name']);

        if ($component == null) {
            $propertyValue = $property[0];
            if (preg_match("/^\"(.+)\"$/",$propertyValue, $regs) or
                preg_match("/^\'(.+)\'$/",$propertyValue, $regs)) {
                    $propertyDef->setValue($regs[1]);
            } else {
                $propertyDef->setExpression($propertyValue);
                if (array_key_exists($propertyValue, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$propertyValue], $propertyDef);
                } else {
                    $this->unresolvedCompRef_[$propertyValue] = array($propertyDef);
                }
            }
        } else {
            $childComponent = $this->_setupComponentDef($component);
            $propertyDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }

        $this->_setupMetaDef($property, $propertyDef, $component);
        
        return $propertyDef;
    }
    
    /**
     * 
     */
    private function _setupInitMethodDef(array $initMethod)
    {
        $name = $initMethod['name'];
        $initMethodDef = new S2Container_InitMethodDefImpl($name);

        if (isset($initMethod[0])) {
            $initMethodDef->setExpression($initMethod[0]);
        }
        $arg = array();
        if(!isset($initMethod['arg'])){
            $arg[] = $initMethod['arg'];
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
        $name = $destroyMethod['name'];
        $destroyMethodDef = new S2Container_DestroyMethodDefImpl($name);

        if (isset($destroyMethod[0])) {
            $destroyMethodDef->setExpression($destroyMethod[0]);
        }

        $arg = array();
        if(isset($destroyMethod["arg"])){
            $destroyMethod["arg"] = array($destroyMethod["arg"]);
        }
        foreach ($destroyMethod["arg"] as $index => $val) {
            $destroyMethodDef->addArgDef($this->_setupArgDef($val));
        }

        return $destroyMethodDef;
    }

    /**
     * 
     */
    private function _setupAspectDef(array $aspect, $targetClassName, $component)
    {
        
        if (!isset($aspect['pointcut'])) {
            $pointcut = new S2Container_PointcutImpl($targetClassName);
        } else {
            $pointcuts = split(",", $aspect['pointcut']);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        }
        
        $aspectDef = new S2Container_AspectDefImpl($pointcut);
        if ($component == null) {
            $aspectValue = $aspect[0];
            if (preg_match("/^\"(.+)\"$/", $aspectValue, $regs) or
                preg_match("/^\'(.+)\'$/", $aspectValue, $regs)) {
                    $aspectDef->setValue($regs[1]);
            } else {
                $aspectDef->setExpression($aspectValue);
                if (array_key_exists($aspectValue, $this->unresolvedCompRef_)) {
                    array_push($this->unresolvedCompRef_[$aspectValue], $aspectDef);
                } else {
                    $this->unresolvedCompRef_[$aspectValue] = array($aspectDef);
                }
            }
        } else {
            $childComponent = $this->_setupComponentDef($component);
            $aspectDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_, $childComponent);
        }
        
        return $aspectDef;
    }
    
    /**
     * 
     */
    private function _setupMetaDef(array $parent, $parentDef, $component)
    {
        if(!is_array($parent['meta'])){
            $parent['meta'] = array($parent['meta']);
        }

        foreach ($parent['meta'] as $val) {
            $name = $val['name'];
            $metaDef = new S2Container_MetaDefImpl($name);

            if (!isset($val['component'])) {
                $metaValue = $val[0];
                if (preg_match("/^\"(.+)\"$/", $metaValue, $regs) or
                    preg_match("/^\'(.+)\'$/",$metaValue, $regs)) {
                        $metaDef->setValue($regs[1]);
                } else {
                    $metaDef->setExpression($metaValue);
                    if (array_key_exists($metaValue, $this->unresolvedCompRef_)) {
                        array_push($this->unresolvedCompRef_[$metaValue], $metaDef);
                    } else {
                        $this->unresolvedCompRef_[$metaValue] = array($metaDef);
                    }
                }
            } else {
                $childComponent = $this->_setupComponentDef($component);
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

    // FIXME!!!!
    private function array_append($array)
    {
        if(is_array($array[0])){
            return $array;
        } else {
            return (array)$array;
        }
    }
}
?>
