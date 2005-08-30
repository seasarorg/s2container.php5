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
abstract class MethodDefImpl implements MethodDef {

    private $methodName_;
    private $argDefSupport_;
    private $container_;
    private $expression_;

    public function MethodDefImpl($methodName=null) {
        $this->argDefSupport_ = new ArgDefSupport();
        if($methodName != null){
            $this->methodName_ = $methodName;
        }
    }

    /**
     * @see MethodDef::getMethodName()
     */
    public function getMethodName() {
        return $this->methodName_;
    }

    /**
     * @see MethodDef::addArgDef()
     */
    public function addArgDef(ArgDef $argDef) {
        $this->argDefSupport_->addArgDef($argDef);
    }

    /**
     * @see ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize() {
        return $this->argDefSupport_->getArgDefSize();
    }

    /**
     * @see ArgDefAware::getArgDef()
     */
    public function getArgDef($index) {
        return $this->argDefSupport_->getArgDef($index);
    }

    /**
     * @see MethodDef::getArgs()
     */
    public function getArgs() {
        $args = array();
        for ($i = 0; $i < $this->getArgDefSize(); ++$i) {
            $args[$i] = $this->getArgDef($i)->getValue();
        }
        return $args;
    }

    /**
     * @see MethodDef::getContainer()
     */
    public function getContainer() {
        return $this->container_;
    }

    /**
     * @see MethodDef::setContainer()
     */
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        $this->argDefSupport_->setContainer($container);
    }

    /**
     * @see MethodDef::getExpression()
     */
    public function getExpression() {
        return $this->expression_;
    }

    /**
     * @see MethodDef::setExpression()
     */
    public function setExpression($expression) {
        $this->expression_ = $expression;
    }
}
?>