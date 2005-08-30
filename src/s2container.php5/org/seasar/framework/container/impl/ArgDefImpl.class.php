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
// $Id: ArgDefImpl.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class ArgDefImpl implements ArgDef {

    private $value_;
    private $container_;
    private $expression_ = "";
    private $exp_ = null;
    private $childComponentDef_ = null;
    private $metaDefSupport_;

    public function ArgDefImpl($value=null) {
        $this->metaDefSupport_ = new MetaDefSupport();
        if($value != null){
            $this->setValue($value);
        }
    }

    /**
     * @see ArgDef::getValue()
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
     * @see ArgDef::getContainer()
     */
    public final function getContainer() {
        return $this->container_;
    }

    /**
     * @see ArgDef::setContaine()
     */
    public final function setContainer($container) {
        $this->container_ = $container;
        if ($this->childComponentDef_ != null) {
            $this->childComponentDef_->setContainer($container);
        }
        $this->metaDefSupport_->setContainer($container);
    }

    /**
     * @see ArgDef::getExpression()
     */
    public final function getExpression() {
        return $this->expression_;
    }

    /**
     * @see ArgDef::setExpression()
     */
    public final function setExpression($expression) {
        $this->expression_ = trim($expression);
        if($this->expression_ == ""){
            $this->exp_ = null;
        }else{
            $this->exp_ = EvalUtil::getExpression($this->expression_);
        }
    }

    /**
     * @see ArgDef::setChildComponentDef()
     */
    public final function setChildComponentDef(ComponentDef $componentDef) {
        if ($this->container_ != null) {
            $componentDef->setContainer($this->container_);
        }
        $this->childComponentDef_ = $componentDef;
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
    public function getMetaDef($indexOrName) {
        return $this->metaDefSupport_->getMetaDef($indexOrName);
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
}
?>