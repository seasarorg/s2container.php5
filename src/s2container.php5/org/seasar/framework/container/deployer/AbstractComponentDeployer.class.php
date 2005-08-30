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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
abstract class AbstractComponentDeployer implements ComponentDeployer {

    private $componentDef_;
    private $constructorAssembler_;
    private $propertyAssembler_;
    private $initMethodAssembler_;
    private $destroyMethodAssembler_;

    public function AbstractComponentDeployer(ComponentDef $componentDef) {
        $this->componentDef_ = $componentDef;
        $this->setupAssembler();
    }

    protected final function getComponentDef() {
        return $this->componentDef_;
    }

    protected final function getConstructorAssembler() {
        return $this->constructorAssembler_;
    }

    protected final function getPropertyAssembler() {
        return $this->propertyAssembler_;
    }

    protected final function getInitMethodAssembler() {
        return $this->initMethodAssembler_;
    }

    protected final function getDestroyMethodAssembler() {
        return $this->destroyMethodAssembler_;
    }

    private function setupAssembler() {
        $autoBindingMode = $this->componentDef_->getAutoBindingMode();
        if (AutoBindingUtil::isAuto($autoBindingMode)) {
            $this->setupAssemblerForAuto();
        } else if (AutoBindingUtil::isConstructor($autoBindingMode)) {
            $this->setupAssemblerForConstructor();
        } else if (AutoBindingUtil::isProperty($autoBindingMode)) {
            $this->setupAssemblerForProperty();
        } else if (AutoBindingUtil::isNone($autoBindingMode)) {
            $this->setupAssemblerForNone();
        } else {
            throw new IllegalArgumentException($autoBindingMode);
        }

        $this->initMethodAssembler_ = new DefaultInitMethodAssembler($this->componentDef_);
        $this->destroyMethodAssembler_ = new DefaultDestroyMethodAssembler($this->componentDef_);

    }

    private function setupAssemblerForAuto() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new AutoPropertyAssembler($this->componentDef_);
    }
    
    private function setupConstructorAssemblerForAuto() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new AutoConstructorAssembler($this->componentDef_);
        }
    }

    private function setupAssemblerForConstructor() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new ManualPropertyAssembler($this->componentDef_);
    }

    private function setupAssemblerForProperty() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ = new ManualConstructorAssembler($this->componentDef_);
        }
        $this->propertyAssembler_ = new AutoPropertyAssembler($this->componentDef_);
    }

    private function setupAssemblerForNone() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new DefaultConstructorAssembler($this->componentDef_);
        }
        if ($this->componentDef_->getPropertyDefSize() > 0) {
            $this->propertyAssembler_ = new ManualPropertyAssembler($this->componentDef_);
        } else {
            $this->propertyAssembler_ = new DefaultPropertyAssembler($this->componentDef_);
        }
    }
}
?>
