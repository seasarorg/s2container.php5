<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2ContainerImpl implements S2Container {

    private $componentDefMap_ = array();
    private $componentDefList_ = array();
    private $namespace_;
    private $path_;
    private $children_ = array();
    private $descendants_ = array();
    private $root_;
    private $inited_ = false;
    private $metaDefSupport_;
    
    public function S2ContainerImpl() {
        $this->metaDefSupport_ = new MetaDefSupport();
        $this->root_ = $this;
        $componentDef = new SimpleComponentDef($this, ContainerConstants::CONTAINER_NAME);
        $this->componentDefMap_[ContainerConstants::CONTAINER_NAME] = $componentDef;
        $this->componentDefMap_['S2Container'] = $componentDef;
    }
    
    public function getRoot() {
        return $this->root_;
    }

    public function setRoot(S2Container $root) {
        $this->root_ = $root;
    }
        
    /**
     * @see S2Container::getComponent()
     */
    public function getComponent($componentKey) {
        return $this->getComponentDef($componentKey)->getComponent();
    }

    /**
     * @see S2Container::injectDependency()
     */
    public function injectDependency($outerComponent, $componentName="") {
        if(is_object($outerComponent)){
            if($componentName != ""){
                $this->getComponentDef($componentName)->injectDependency($outerComponent);
            }else{
                $this->getComponentDef(get_class($outerComponent))->injectDependency($outerComponent);
            }
        }
    }

    /**
     * @see S2Container::register()
     */
    public function register($component,$componentName="") {
        if($component instanceof ComponentDef){
            $this->register0($component);
            array_push($this->componentDefList_,$component);
        }else if(is_object($component)){
            $this->register(new SimpleComponentDef($component,trim($componentName)));
        }else {
            $this->register(new ComponentDefImpl($component, trim($componentName)));
        }
    }

    private function register0(ComponentDef $componentDef) {
        if ($componentDef->getContainer() == null) {
            $componentDef->setContainer($this);
        }    
        $this->registerByClass($componentDef);
        $this->registerByName($componentDef);
    }

    private function registerByClass(ComponentDef $componentDef) {
        $classes = $this->getAssignableClasses($componentDef->getComponentClass());
        for ($i = 0; $i < count($classes); ++$i) {
            $this->registerMap($classes[$i], $componentDef);
        }
    }

    private function registerByName(ComponentDef $componentDef) {
        $componentName = $componentDef->getComponentName();
        if ($componentName != "") {
            $this->registerMap($componentName, $componentDef);
        }
    }

    private function registerMap($key, ComponentDef $componentDef) {
        if (array_key_exists($key,$this->componentDefMap_)) {
            $this->processTooManyRegistration($key, $componentDef);
        } else {
            $this->componentDefMap_[$key] = $componentDef;
        }
    }

    /**
     * @see S2Container::getComponentDefSize()
     */
    public function getComponentDefSize() {
        return count($this->componentDefList_);
    }

    /**
     * @see S2Container::getComponentDef()
     */
    public function getComponentDef($key){
        if(is_int($key)){
        	if(!isset($this->componentDefList_[$key])){
        		throw new ComponentNotFoundRuntimeException($key);
        	}
            return $this->componentDefList_[$key];
        }
        if(is_object($key)){
            $key = get_class($key);
        }

        $cd = $this->getComponentDef0($key);
        if ($cd == null) {
            throw new ComponentNotFoundRuntimeException($key);
        }
        return $cd;
    }

    private function getComponentDef0($key) {
        $cd = null;
        if(array_key_exists($key,$this->componentDefMap_)){
            $cd = $this->componentDefMap_[$key];
            if ($cd != null) {
                return $cd;
            }
        }
        if(preg_match("/(.+)\.(.+)/",$key,$ret)){
            if ($this->hasComponentDef($ret[1])) {
                $child = $this->getComponent($ret[1]);
                if ($child->hasComponentDef($ret[2])) {
                    return $child->getComponentDef($ret[2]);
                }
            }
        }
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
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
    public function hasComponentDef($componentKey) {
        return $this->getComponentDef0($componentKey) != null;
    }

    /**
     * @see S2Container::hasDescendant()
     */
    public function hasDescendant($path) {
        return array_key_exists($path,$this->descendants_);
    }
    
    public function getDescendant($path) {
        $descendant = null;
        if(array_key_exists($path,$this->descendants_)){
            $descendant = $this->descendants_[$path];
        }
        if ($descendant != null) {
            return $descendant;
        } else {
            throw new ContainerNotRegisteredRuntimeException($path);
        }
    }
    
    public function registerDescendant(S2Container $descendant) {
        $this->descendants_[$descendant->getPath()] = $descendant;
    }

    /**
     * @see S2Container::includeChild()
     */
    public function includeChild(S2Container $child) {
        $child->setRoot($this->getRoot());
        array_push($this->children_,$child);
        $ns = $child->getNamespace();
        if ($ns != null) {
            $this->registerMap($ns, new S2ContainerComponentDef($child, $ns));
        }
    }

    /**
     * @see S2Container::getChildSize()
     */
    public function getChildSize() {
        return count($this->children_);
    }

    /**
     * @see S2Container::getChild()
     */
    public function getChild($index) {
    	if(!isset($this->children_[$index])){
    		throw new ContainerNotRegisteredRuntimeException("Child:".$index);
    	}
        return $this->children_[$index];
    }

    /**
     * @see S2Container::init()
     */
    public function init() {
        if ($this->inited_) {
            return;
        }
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
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
    public function destroy() {
        if (!$this->inited_) {
            return;
        }
        for ($i = $this->getComponentDefSize() - 1; 0 <= $i; --$i) {
            try {
                $this->getComponentDef($i)->destroy();
            } catch (Exception $e) {
                print $e->getMessage() . "\n";
            }

        }
        for ($i = $this->getChildSize() - 1; 0 <= $i; --$i) {
            $this->getChild($i)->destroy();
        }
        $this->inited_ = false;
    }

    /**
     * @see S2Container::getNamespace()
     */
    public function getNamespace() {
        return $this->namespace_;
    }

    /**
     * @see S2Container::setNamespace()
     */
    public function setNamespace($namespace) {
        $this->namespace_ = $namespace;
        $this->componentDefMap_[$this->namespace_] = 
            new SimpleComponentDef($this,$this->namespace_);
    }

    public function getPath() {
        return $this->path_;
    }

    public function setPath($path) {
        $this->path_ = $path;
    }
 
    /**
     * @see MetaDefAware::addMetaDef()
     */
    public function addMetaDef(MetaDef $metaDef) {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    
    /**
     * @see MetaDefAware::getMetaDef()
     */
    public function getMetaDef($name) {
        return $this->metaDefSupport_->getMetaDef($name);
    }
    
    /**
     * @see MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name) {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    
    /**
     * @see MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize() {
        return $this->metaDefSupport_->getMetaDefSize();
    }
    
    private static function &getAssignableClasses($componentClass) {
        
        if(! $componentClass instanceof ReflectionClass){
        	return array();
        }
        $ref = $componentClass;
        
        $classes = array();
        $interfaces = $ref->getInterfaces();
        for($i=0;$i<count($interfaces);$i++) {
            array_push($classes,$interfaces[$i]->getName());
        }
        while($ref != null){
            array_push($classes,$ref->getName());
            $ref = $ref->getParentClass();
        }
        
        return $classes;
    }
    
    private function  processTooManyRegistration($key,
            ComponentDef $componentDef) {

        $cd = $this->componentDefMap_[$key];
        if ($cd instanceof TooManyRegistrationComponentDef) {
            $cd->addComponentClass($componentDef->getComponentClass());
        } else {
            $tmrcf = new TooManyRegistrationComponentDef($key);
            $tmrcf->addComponentClass($cd->getComponentClass());
            $tmrcf->addComponentClass($componentDef->getComponentClass());
            $this->componentDefMap_[$key] = $tmrcf;
        }
    }
}
?>