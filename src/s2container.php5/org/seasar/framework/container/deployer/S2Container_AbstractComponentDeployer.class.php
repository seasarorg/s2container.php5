<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
abstract class S2Container_AbstractComponentDeployer
    implements S2Container_ComponentDeployer
{
    private $componentDef_;
    private $constructorAssembler_;
    private $propertyAssembler_;
    private $initMethodAssembler_;
    private $destroyMethodAssembler_;

    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        $this->componentDef_ = $componentDef;
        $this->_setupAssembler();
    }

    /**
     * @return S2Container_ComponentDef
     */
    protected final function getComponentDef()
    {
        return $this->componentDef_;
    }

    /**
     * 
     */
    protected final function getConstructorAssembler()
    {
        return $this->constructorAssembler_;
    }

    /**
     * 
     */
    protected final function getPropertyAssembler()
    {
        return $this->propertyAssembler_;
    }

    /**
     * 
     */
    protected final function getInitMethodAssembler()
    {
        return $this->initMethodAssembler_;
    }

    /**
     * 
     */
    protected final function getDestroyMethodAssembler()
    {
        return $this->destroyMethodAssembler_;
    }

    /**
     * 
     */
    private function _setupAssembler()
    {
        $autoBindingMode = $this->componentDef_->getAutoBindingMode();
        if (S2Container_AutoBindingUtil::isAuto($autoBindingMode)) {
            $this->_setupAssemblerForAuto();
        } else if (S2Container_AutoBindingUtil::isConstructor($autoBindingMode)) {
            $this->_setupAssemblerForConstructor();
        } else if (S2Container_AutoBindingUtil::isProperty($autoBindingMode)) {
            $this->_setupAssemblerForProperty();
        } else if (S2Container_AutoBindingUtil::isNone($autoBindingMode)) {
            $this->_setupAssemblerForNone();
        } else {
            throw new S2Container_IllegalArgumentException($autoBindingMode);
        }
        $this->initMethodAssembler_ = 
            new S2Container_DefaultInitMethodAssembler($this->componentDef_);
        $this->destroyMethodAssembler_ = 
            new S2Container_DefaultDestroyMethodAssembler($this->componentDef_);
    }

    /**
     * 
     */
    private function _setupAssemblerForAuto()
    {
        $this->_setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = 
            new S2Container_AutoPropertyAssembler($this->componentDef_);
    }
    
    /**
     * 
     */
    private function _setupConstructorAssemblerForAuto()
    {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        } else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new S2Container_ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new S2Container_AutoConstructorAssembler($this->componentDef_);
        }
    }

    /**
     * 
     */
    private function _setupAssemblerForConstructor()
    {
        $this->_setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = 
            new S2Container_ManualPropertyAssembler($this->componentDef_);
    }

    /**
     * 
     */
    private function _setupAssemblerForProperty()
    {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ = 
                new S2Container_ManualConstructorAssembler($this->componentDef_);
        }
        $this->propertyAssembler_ = 
            new S2Container_AutoPropertyAssembler($this->componentDef_);
    }

    /**
     * 
     */
    private function _setupAssemblerForNone()
    {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        } else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new S2Container_ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new S2Container_DefaultConstructorAssembler($this->componentDef_);
        }
        if ($this->componentDef_->getPropertyDefSize() > 0) {
            $this->propertyAssembler_ = 
                new S2Container_ManualPropertyAssembler($this->componentDef_);
        } else {
            $this->propertyAssembler_ = 
                new S2Container_DefaultPropertyAssembler($this->componentDef_);
        }
    }
}
?>
