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
// $Id: ComponentDefImpl.class.php,v 1.2 2005/05/31 16:40:22 klove Exp $
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class ComponentDefImpl implements ComponentDef {

    private $componentClass_;

    private $componentName_;

    private $concreteClass_;

    private $container_;

    private $expression_;

    private $argDefSupport_;

    private $propertyDefSupport_;

    private $initMethodDefSupport_;

    private $destroyMethodDefSupport_;

    private $aspectDefSupport_;
    
    private $metaDefSupport_;

    private $instanceMode_ = ContainerConstants::INSTANCE_SINGLETON;

    private $autoBindingMode_ = ContainerConstants::AUTO_BINDING_AUTO;

    private $componentDeployer_;

    public function ComponentDefImpl($componentClass="", $componentName="") {
        if($componentClass!=""){
        	$this->componentClass_ = new ReflectionClass($componentClass);
        }
        $this->componentName_ = $componentName;
        $this->argDefSupport_ = new ArgDefSupport();
        $this->propertyDefSupport_ = new PropertyDefSupport();
        $this->initMethodDefSupport_ = new InitMethodDefSupport();
        $this->destroyMethodDefSupport_ = new DestroyMethodDefSupport();
        $this->aspectDefSupport_ = new AspectDefSupport();
        $this->metaDefSupport_ = new MetaDefSupport();
    }

    /**
     * @see ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->getComponentDeployer()->deploy();
    }

    /**
     * @see ComponentDef::injectDependency()
     */
    public function injectDependency($outerComponent) {
        $this->getComponentDeployer()->injectDependency($outerComponent);
    }

    /**
     * @see ComponentDef::getComponentClass()
     */
    public final function getComponentClass() {
        return $this->componentClass_;
    }

    /**
     * @see ComponentDef::getComponentClass()
     */
    public final function setComponentClass(ReflectionClass $componentClass) {
        $this->componentClass_ = $componentClass;
    }

    /**
     * @see ComponentDef::getComponentName()
     */
    public final function getComponentName() {
        return $this->componentName_;
    }

    /**
     * @see ComponentDef::getConcreteClass()
     */
    public final function getConcreteClass() {
        return $this->componentClass_;
    }

    /**
     * @see ComponentDef::getContainer()
     */
    public final function getContainer() {
        return $this->container_;
    }

    /**
     * @see ComponentDef::setContainer()
     */
    public final function setContainer(S2Container $container) {
        $this->container_ = $container;
        $this->argDefSupport_->setContainer($container);
        $this->metaDefSupport_->setContainer($container);
        $this->propertyDefSupport_->setContainer($container);
        $this->initMethodDefSupport_->setContainer($container);
        $this->destroyMethodDefSupport_->setContainer($container);
        $this->aspectDefSupport_->setContainer($container);
    }

    /**
     * @see ComponentDef::addArgDef()
     */
    public function addArgDef(ArgDef $argDef) {
        $this->argDefSupport_->addArgDef($argDef);
    }

    /**
     * @see ComponentDef::addPropertyDef()
     */
    public function addPropertyDef(PropertyDef $propertyDef) {
        $this->propertyDefSupport_->addPropertyDef($propertyDef);
    }

    /**
     * @see InitMethodDefAware::addInitMethodDef()
     */
    public function addInitMethodDef(InitMethodDef $methodDef) {
        $this->initMethodDefSupport_->addInitMethodDef($methodDef);
    }

    /**
     * @see DestroyMethodDefAware::addDestroyMethodDef()
     */
    public function addDestroyMethodDef(DestroyMethodDef $methodDef) {
        $this->destroyMethodDefSupport_->addDestroyMethodDef($methodDef);
    }

    /**
     * @see ComponentDef::addAspectDef()
     */
    public function addAspectDef(AspectDef $aspectDef) {
        $this->aspectDefSupport_->addAspectDef($aspectDef);
        $this->concreteClass_ = null;
    }

    /**
     * @see ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize() {
        return $this->argDefSupport_->getArgDefSize();
    }

    /**
     * @see PropertyDefAware::getPropertyDefSize()
     */
    public function getPropertyDefSize() {
        return $this->propertyDefSupport_->getPropertyDefSize();
    }

    /**
     * @see InitMethodDefAware::getInitMethodDefSize()
     */
    public function getInitMethodDefSize() {
        return $this->initMethodDefSupport_->getInitMethodDefSize();
    }

    /**
     * @see DestroyMethodDefAware::getDestroyMethodDefSize()
     */
    public function getDestroyMethodDefSize() {
        return $this->destroyMethodDefSupport_->getDestroyMethodDefSize();
    }

    /**
     * @see AspectDefAware::getAspectDefSize()
     */
    public function getAspectDefSize() {
        return $this->aspectDefSupport_->getAspectDefSize();
    }

    /**
     * @see ComponentDef::getInstanceMode()
     */
    public function getInstanceMode() {
        return $this->instanceMode_;
    }

    /**
     * @see ComponentDef::setInstanceMode()
     */
    public function setInstanceMode($instanceMode) {
        if (InstanceModeUtil::isSingleton($instanceMode)
                || InstanceModeUtil::isPrototype($instanceMode)
                || InstanceModeUtil::isRequest($instanceMode)
                || InstanceModeUtil::isSession($instanceMode)
                || InstanceModeUtil::isOuter($instanceMode)) {

            $this->instanceMode_ = $instanceMode;
        } else {
            throw new IllegalArgumentException($instanceMode);
        }
    }

    /**
     * @see ComponentDef::getAutoBindingMode()
     */
    public function getAutoBindingMode() {
        return $this->autoBindingMode_;
    }

    /**
     * @see ComponentDef::setAutoBindingMode()
     */
    public function setAutoBindingMode($autoBindingMode) {
        if (AutoBindingUtil::isAuto($autoBindingMode)
                || AutoBindingUtil::isConstructor($autoBindingMode)
                || AutoBindingUtil::isProperty($autoBindingMode)
                || AutoBindingUtil::isNone($autoBindingMode)) {

            $this->autoBindingMode_ = $autoBindingMode;
        } else {
            throw new IllegalArgumentException(autoBindingMode);
        }
    }

    /**
     * @see ComponentDef::init()
     */
    public function init() {
        $this->getComponentDeployer()->init();
    }

    /**
     * @see ComponentDef::destroy()
     */
    public function destroy() {
        $this->getComponentDeployer()->destroy();
    }

    /**
     * @see ComponentDef::getExpression()
     */
    public function getExpression() {
        return $this->expression_;
    }

    /**
     * @see ComponentDef::setExpression()
     */
    public function setExpression($expression) {
        $this->expression_ = $expression;
    }

    /**
     * @see ArgDefAware::getArgDef()
     */
    public function getArgDef($index) {
        return $this->argDefSupport_->getArgDef($index);
    }

    /**
     * @see PropertyDefAware::getPropertyDef()
     */
    public function getPropertyDef($index) {
        return $this->propertyDefSupport_->getPropertyDef($index);
    }

    /**
     * @see PropertyDefAware::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName) {
        return $this->propertyDefSupport_->hasPropertyDef($propertyName);
    }

    /**
     * @see InitMethodDefAware::getInitMethodDef()
     */
    public function getInitMethodDef($index) {
        return $this->initMethodDefSupport_->getInitMethodDef($index);
    }

    /**
     * @see DestroyMethodDefAware::getDestroyMethodDef()
     */
    public function getDestroyMethodDef($index) {
        return $this->destroyMethodDefSupport_->getDestroyMethodDef($index);
    }

    /**
     * @see AspectDefAware::getAspectDef()
     */
    public function getAspectDef($index) {
        return $this->aspectDefSupport_->getAspectDef($index);
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

    private function getComponentDeployer() {

        if ($this->componentDeployer_ == null) {
            $this->componentDeployer_ = ComponentDeployerFactory::create($this);
        }
        return $this->componentDeployer_;
    }   
}
?>