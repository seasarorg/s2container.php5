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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_ArgDefImpl implements S2Container_ArgDef {

    private $value_;
    private $container_;
    private $expression_ = "";
    private $exp_ = null;
    private $childComponentDef_ = null;
    private $metaDefSupport_;

    public function S2Container_ArgDefImpl($value=null) {
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
        if($value != null){
            $this->setValue($value);
        }
    }

    /**
     * @see S2Container_ArgDef::getValue()
     */
    public final function getValue() {
        if ($this->exp_ != null) {
            return eval($this->exp_);
        }
        if ($this->childComponentDef_ != null) {
            return $this->childComponentDef_->getComponent();
        }
        return $this->value_;
    }

    public final function setValue($value) {
        $this->value_ = $value;
    }
    
    /**
     * @see S2Container_ArgDef::getContainer()
     */
    public final function getContainer() {
        return $this->container_;
    }

    /**
     * @see S2Container_ArgDef::setContaine()
     */
    public final function setContainer($container) {
        $this->container_ = $container;
        if ($this->childComponentDef_ != null) {
            $this->childComponentDef_->setContainer($container);
        }
        $this->metaDefSupport_->setContainer($container);
    }

    /**
     * @see S2Container_ArgDef::getExpression()
     */
    public final function getExpression() {
        return $this->expression_;
    }

    /**
     * @see S2Container_ArgDef::setExpression()
     */
    public final function setExpression($expression) {
        $this->expression_ = trim($expression);
        if($this->expression_ == ""){
            $this->exp_ = null;
        }else{
            $this->exp_ = S2Container_EvalUtil::getExpression($this->expression_);
        }
    }

    /**
     * @see S2Container_ArgDef::setChildComponentDef()
     */
    public final function setChildComponentDef(S2Container_ComponentDef $componentDef) {
        if ($this->container_ != null) {
            $componentDef->setContainer($this->container_);
        }
        $this->childComponentDef_ = $componentDef;
    }
    
    /**
     * @see S2Container_MetaDefAware::addMetaDef()
     */
    public function addMetaDef(S2Container_MetaDef $metaDef) {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDef()
     */
    public function getMetaDef($indexOrName) {
        return $this->metaDefSupport_->getMetaDef($indexOrName);
    }

    /**
     * @see S2Container_MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name) {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize() {
        return $this->metaDefSupport_->getMetaDefSize();
    }
}
?>