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
// $Id$
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2ContainerImpl implements S2Container
{
    private $componentDefMap_ = array();
    private $componentDefList_ = array();
    private $namespace_;
    private $path_;
    private $children_ = array();
    private $descendants_ = array();
    private $root_;
    private $inited_ = false;
    private $metaDefSupport_;

    /**
     * 
     */    
    public function __construct()
    {
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
        $this->root_ = $this;
        $componentDef = new S2Container_SimpleComponentDef($this,
                             S2Container_ContainerConstants::CONTAINER_NAME);
        $this->componentDefMap_[S2Container_ContainerConstants::CONTAINER_NAME] = 
                                                                 $componentDef;
        $this->componentDefMap_['S2Container'] = $componentDef;
    }
    
    /**
     * @return S2Container
     */
    public function getRoot()
    {
        return $this->root_;
    }

    /**
     * @param S2Container
     */
    public function setRoot(S2Container $root)
    {
        $this->root_ = $root;
    }
        
    /**
     * @see S2Container::getComponent()
     */
    public function getComponent($componentKey)
    {
        return $this->getComponentDef($componentKey)->getComponent();
    }

    /**
     * @see S2Container::findComponents()
     */
    public function findComponents($componentKey)
    {
        $componentDefs = $this->findComponentDefs($componentKey);
        $components = array();
        foreach ($componentDefs as $componentDef) {
            $components[] = $componentDef->getComponent();
        }
        return $components;
    }

    /**
     * @see S2Container::injectDependency()
     */
    public function injectDependency($outerComponent, $componentName = "")
    {
        if (is_object($outerComponent)) {
            if ($componentName != "") {
                $this->getComponentDef($componentName)->
                                             injectDependency($outerComponent);
            } else {
                $this->getComponentDef(get_class($outerComponent))->
                                             injectDependency($outerComponent);
            }
        }
    }

    /**
     * @see S2Container::register()
     */
    public function register($component,$componentName = "")
    {
        if ($component instanceof S2Container_ComponentDef) {
            $this->_register0($component);
            $this->componentDefList_[] = $component;
        } else if (is_object($component)) {
            $this->register(new S2Container_SimpleComponentDef($component,
                                                         trim($componentName)));
        } else {
            $this->register(new S2Container_ComponentDefImpl($component,
                                                         trim($componentName)));
        }
    }

    /**
     * @param S2Container_ComponentDef
     */
    private function _register0(S2Container_ComponentDef $componentDef)
    {
        if ($componentDef->getContainer() == null) {
            $componentDef->setContainer($this);
        }    
        $this->_registerByClass($componentDef);
        $this->_registerByName($componentDef);
    }

    /**
     * @param S2Container_ComponentDef
     */
    private function _registerByClass(S2Container_ComponentDef $componentDef)
    {
        $classes = $this->getAssignableClasses($componentDef->getComponentClass());
        $o = count($classes);
        $componentName = $componentDef->getComponentName();
        for ($i = 0; $i < $o; ++$i) {
            if ($classes[$i] != $componentName) {
                $this->_registerMap($classes[$i], $componentDef);
            }
        }
    }

    /**
     * @param S2Container_ComponentDef
     */
    private function _registerByName(S2Container_ComponentDef $componentDef)
    {
        $componentName = $componentDef->getComponentName();
        if ($componentName != "") {
            $this->_registerMap($componentName, $componentDef);
        }
    }

    /**
     * @param string key
     * @param S2Container_ComponentDef
     */
    private function _registerMap($key, S2Container_ComponentDef $componentDef)
    {
        if (array_key_exists($key,$this->componentDefMap_)) {
            $this->_processTooManyRegistration($key, $componentDef);
        } else {
            $this->componentDefMap_[$key] = $componentDef;
        }
    }

    /**
     * @see S2Container::getComponentDefSize()
     */
    public function getComponentDefSize()
    {
        return count($this->componentDefList_);
    }

    /**
     * @see S2Container::getComponentDef()
     */
    public function getComponentDef($key)
    {
        if (is_int($key)) {
            if (!isset($this->componentDefList_[$key])) {
                throw new S2Container_ComponentNotFoundRuntimeException($key);
            }
            return $this->componentDefList_[$key];
        }
        if (is_object($key)) {
            $key = get_class($key);
        }

        $cd = $this->_internalGetComponentDef($key);
        if ($cd == null) {
            throw new S2Container_ComponentNotFoundRuntimeException($key);
        }
        return $cd;
    }

    /**
     * @see S2Container::findComponentDefs()
     */
    public function findComponentDefs($key)
    {
        $cd = $this->_internalGetComponentDef($key);
        if ($cd == null) {
            return array();
        } else if ($cd instanceof S2Container_TooManyRegistrationComponentDef) {
            return $cd->getComponentDefs();
        }
        return array($cd);
    }

    /**
     * @param string key
     */
    private function _internalGetComponentDef($key)
    {
        $cd = null;
        if (array_key_exists($key,$this->componentDefMap_)) {
            $cd = $this->componentDefMap_[$key];
            if ($cd != null) {
                return $cd;
            }
        }

        if (preg_match("/(.+)" . 
                S2Container_ContainerConstants::NS_SEP . "(.+)/",$key,$ret)) {
            if ($this->hasComponentDef($ret[1])) {
                $child = $this->getComponent($ret[1]);
                if ($child->hasComponentDef($ret[2])) {
                    return $child->getComponentDef($ret[2]);
                }
            }
        }
        $o = $this->getChildSize();
        for ($i = 0; $i < $o; ++$i) {
            $child = $this->getChild($i);
            if ($child->hasComponentDef($key)) {
                return $child->getComponentDef($key);
            }
        }
        return null;
    }

    /**
     * @see S2Container::hasComponentDef()
     */
    public function hasComponentDef($componentKey)
    {
        return $this->_internalGetComponentDef($componentKey) != null;
    }

    /**
     * @see S2Container::hasDescendant()
     */
    public function hasDescendant($path)
    {
        return array_key_exists($path,$this->descendants_);
    }
    
    /**
     * @param string path
     * @return S2Container
     */
    public function getDescendant($path)
    {
        $descendant = null;
        if (array_key_exists($path,$this->descendants_)) {
            $descendant = $this->descendants_[$path];
        }
        if ($descendant != null) {
            return $descendant;
        } else {
            throw new S2Container_ContainerNotRegisteredRuntimeException($path);
        }
    }
    
    /**
     * @param S2Container
     */
    public function registerDescendant(S2Container $descendant)
    {
        $this->descendants_[$descendant->getPath()] = $descendant;
    }

    /**
     * @see S2Container::includeChild()
     */
    public function includeChild(S2Container $child)
    {
        $child->setRoot($this->getRoot());
        $this->children_[] = $child;
        $ns = $child->getNamespace();
        if ($ns != null) {
            $this->_registerMap($ns, new S2ContainerComponentDef($child, $ns));
        }
    }

    /**
     * @see S2Container::getChildSize()
     */
    public function getChildSize()
    {
        return count($this->children_);
    }

    /**
     * @see S2Container::getChild()
     */
    public function getChild($index)
    {
        if (!isset($this->children_[$index])) {
            throw new 
                S2Container_ContainerNotRegisteredRuntimeException("Child:" . $index);
        }
        return $this->children_[$index];
    }

    /**
     * @see S2Container::init()
     */
    public function init()
    {
        if ($this->inited_) {
            return;
        }
        $o = $this->getChildSize();
        for ($i = 0; $i < $o; ++$i) {
            $this->getChild($i)->init();
        }
        for ($i = 0; $i < $this->getComponentDefSize(); ++$i) {
            $this->getComponentDef($i)->init();
        }
        $this->inited_ = true;
    }

    /**
     * @see S2Container::destroy()
     */
    public function destroy()
    {
        if (!$this->inited_) {
            return;
        }
        $o = $this->getComponentDefSize() - 1;
        for ($i = $o; 0 <= $i; --$i) {
            try {
                $this->getComponentDef($i)->destroy();
            } catch (Exception $e) {
                print $e->getMessage() . "\n";
            }

        }
        $o = $this->getChildSize() - 1;
        for ($i = $o; 0 <= $i; --$i) {
            $this->getChild($i)->destroy();
        }
        $this->inited_ = false;
    }

    /**
     * @see S2Container::reconstruct()
     */
    public function reconstruct($mode = 
                                 S2Container_ComponentDef::RECONSTRUCT_NORMAL)
    {
        $c = $this->getChildSize();
        for ($i = 0; $i < $c; ++$i) {
            $this->getChild($i)->reconstruct($mode);
        }

        $componentDef = $this->
            componentDefMap_[S2Container_ContainerConstants::CONTAINER_NAME]->
                                                            reconstruct($mode);

        $c = $this->getComponentDefSize();
        for ($i = 0; $i < $c; ++$i) {
            if ($this->getComponentDef($i)->reconstruct($mode) and 
               $mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL) {
                $this->_registerByClass($this->getComponentDef($i));
            }
        }
    }

    /**
     * @see S2Container::getNamespace()
     */
    public function getNamespace()
    {
        return $this->namespace_;
    }

    /**
     * @see S2Container::setNamespace()
     */
    public function setNamespace($namespace)
    {
        $this->namespace_ = $namespace;
        $this->componentDefMap_[$this->namespace_] = 
            new S2Container_SimpleComponentDef($this,$this->namespace_);
    }

    public function getPath()
    {
        return $this->path_;
    }

    public function setPath($path)
    {
        $this->path_ = $path;
    }
 
    /**
     * @see S2Container_MetaDefAware::addMetaDef()
     */
    public function addMetaDef(S2Container_MetaDef $metaDef)
    {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDef()
     */
    public function getMetaDef($name)
    {
        return $this->metaDefSupport_->getMetaDef($name);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name)
    {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize()
    {
        return $this->metaDefSupport_->getMetaDefSize();
    }
    
    /**
     * @param ReflectionClass
     * @param array 
     */
    private static function getAssignableClasses($componentClass)
    {
        if (! $componentClass instanceof ReflectionClass) {
            return array();
        }
        
        $classes = array();
        $interfaces = S2Container_ClassUtil::getInterfaces($componentClass);
        $o = count($interfaces);
        for ($i = 0; $i < $o; $i++) {
            $classes[] = $interfaces[$i]->getName();
        }

        $ref = $componentClass;
        if(!$ref->isInterface()){
            while ($ref != null) {
                $classes[] = $ref->getName();
                $ref = $ref->getParentClass();
            }
        }
        
        return $classes;
    }
    
    /**
     * @param string key
     * @param S2Container_ComponentDef
     */
    private function _processTooManyRegistration($key,
                          S2Container_ComponentDef $componentDef)
    {
        $cd = $this->componentDefMap_[$key];
        if ($cd instanceof S2Container_TooManyRegistrationComponentDef) {
            $cd->addComponentDef($componentDef);
        } else {
            $tmrcf = new S2Container_TooManyRegistrationComponentDefImpl($key);
            $tmrcf->addComponentDef($cd);
            $tmrcf->addComponentDef($componentDef);
            $this->componentDefMap_[$key] = $tmrcf;
        }
    }
}
?>
