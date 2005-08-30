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
// $Id: SimpleComponentDef.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * コンポーネントのインスタンスを直接返す場合に使用されます。
 * 
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class SimpleComponentDef implements ComponentDef {

    private $component_;
    private $componentClass_;
    private $componentName_;
    private $container_;

    public function SimpleComponentDef($component,$componentName="") {
            
        $this->component_ = $component;
        $this->componentClass_ = new ReflectionClass($component);
        $this->componentName_ = $componentName;
    }

    /**
     * @see ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->component_;
    }
    
    /**
     * @see ComponentDef::injectDependency()
     */
    public function injectDependency($outerComponent) {
        throw new UnsupportedOperationException("injectDependency");
    }

    /**
     * @see ComponentDef::getContainer()
     */
    public function getContainer() {
        return $this->container_;
    }

    /**
     * @see ComponentDef::setContainer()
     */
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
    }

    /**
     * @see ComponentDef::getComponentClass()
     */
    public function getComponentClass() {
        return $this->componentClass_;
    }

    /**
     * @see ComponentDef::getComponentName()
     */
    public function getComponentName() {
        return $this->componentName_;
    }

    /**
     * @see ComponentDef::getConcreteClass()
     */
    public function getConcreteClass() {
        return $this->componentClass_;
    }

    /**
     * @see ComponentDef::addArgDef()
     */
    public function addArgDef(ArgDef $constructorArgDef) {
        throw new UnsupportedOperationException("addArgDef");
    }

    /**
     * @see ComponentDef::addPropertyDef()
     */
    public function addPropertyDef(PropertyDef $propertyDef) {
        throw new UnsupportedOperationException("addPropertyDef");
    }

    /**
     * @see InitMethodDefAware::addInitMethodDef()
     */
    public function addInitMethodDef(InitMethodDef $methodDef) {
        throw new UnsupportedOperationException("addInitMethodDef");
    }
    
    /**
     * @see DestroyMethodDefAware::addDestroyMethodDef()
     */
    public function addDestroyMethodDef(DestroyMethodDef $methodDef) {
        throw new UnsupportedOperationException("addDestroyMethodDef");
    }

    /**
     * @see ComponentDef::addAspectDef()
     */
    public function addAspectDef(AspectDef $aspectDef) {
        throw new UnsupportedOperationException("addAspectDef");
    }

    /**
     * @see ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize() {
        throw new UnsupportedOperationException("getArgDefSize");
    }

    /**
     * @see PropertyDefAware::getPropertyDefSize()
     */
    public function getPropertyDefSize() {
        throw new UnsupportedOperationException("getPropertyDefSize");
    }

    /**
     * @see InitMethodDefAware::getInitMethodDefSize()
     */
    public function getInitMethodDefSize() {
        throw new UnsupportedOperationException("getInitMethodDefSize");
    }
    
    /**
     * @see DestroyMethodDefAware::getDestroyMethodDefSize()
     */
    public function getDestroyMethodDefSize() {
        throw new UnsupportedOperationException("getDestroyMethodDefSize");
    }

    /**
     * @see AspectDefAware::getAspectDefSize()
     */
    public function getAspectDefSize() {
        throw new UnsupportedOperationException("getAspectDefSize");
    }

    /**
     * @see ArgDefAware::getArgDef()
     */
    public function getArgDef($index) {
        throw new UnsupportedOperationException("getArgDef");
    }

    /**
     * @see PropertyDefAware::getPropertyDef()
     */
    public function getPropertyDef($index) {
        throw new UnsupportedOperationException("getPropertyDef");
    }

    /**
     * @see PropertyDefAware::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName) {
        throw new UnsupportedOperationException("hasPropertyDef");
    }

    /**
     * @see InitMethodDefAware::getInitMethodDef()
     */
    public function getInitMethodDef($index) {
        throw new UnsupportedOperationException("getInitMethodDef");
    }
    
    /**
     * @see DestroyMethodDefAware::getDestroyMethodDef()
     */
    public function getDestroyMethodDef($index) {
        throw new UnsupportedOperationException("getDestroyMethodDef");
    }

    /**
     * @see AspectDefAware::getAspectDef()
     */
    public function getAspectDef($index) {
        throw new UnsupportedOperationException("getAspectDef");
    }
    
    /**
     * @see MetaDefAware::addMetaDef()
     */
    public function addMetaDef(MetaDef $metaDef) {
        throw new UnsupportedOperationException("addMetaDef");
    }
    
    /**
     * @see MetaDefAware::getMetaDef()
     */
    public function getMetaDef($index) {
        throw new UnsupportedOperationException("getMetaDef");
    }

    /**
     * @see MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name) {
        throw new UnsupportedOperationException("getMetaDefs");
    }
    
    /**
     * @see MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize() {
        throw new UnsupportedOperationException("getMetaDefSize");
    }

    /**
     * @see ComponentDef::getExpression()
     */
    public function getExpression() {
        throw new UnsupportedOperationException("getExpression");
    }

    /**
     * @see ComponentDef::setExpression()
     */
    public function setExpression($str) {
        throw new UnsupportedOperationException("setExpression");
    }
    
    /**
     * @see ComponentDef::getInstanceMode()
     */
    public function getInstanceMode() {
        throw new UnsupportedOperationException("getInstanceMode");
    }

    /**
     * @see ComponentDef::setInstanceMode()
     */
    public function setInstanceMode($instanceMode) {
        throw new UnsupportedOperationException("setInstanceMode");
    }

    /**
     * @see ComponentDef::getAutoBindingMode()
     */
    public function getAutoBindingMode() {
        throw new UnsupportedOperationException("getAutoBindingMode");
    }

    /**
     * @see ComponentDef::setAutoBindingMode()
     */
    public function setAutoBindingMode($autoBindingMode) {
        throw new UnsupportedOperationException("setAutoBindingMode");
    }

    /**
     * @see ComponentDef::init()
     */
    public function init() {}

    /**
     * @see ComponentDef::destroy()
     */
    public function destroy() {}
}
?>