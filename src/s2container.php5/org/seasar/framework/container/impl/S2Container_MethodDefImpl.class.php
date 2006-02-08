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
abstract class S2Container_MethodDefImpl
    implements S2Container_MethodDef
{
    private $methodName_;
    private $argDefSupport_;
    private $container_;
    private $expression_;

    /**
     * @param stirng method name
     */
    public function __construct($methodName = null)
    {
        $this->argDefSupport_ = new S2Container_ArgDefSupport();
        if ($methodName != null) {
            $this->methodName_ = $methodName;
        }
    }

    /**
     * @see S2Container_MethodDef::getMethodName()
     */
    public function getMethodName()
    {
        return $this->methodName_;
    }

    /**
     * @see S2Container_MethodDef::addArgDef()
     */
    public function addArgDef(S2Container_ArgDef $argDef)
    {
        $this->argDefSupport_->addArgDef($argDef);
    }

    /**
     * @see S2Container_ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize()
    {
        return $this->argDefSupport_->getArgDefSize();
    }

    /**
     * @see S2Container_ArgDefAware::getArgDef()
     */
    public function getArgDef($index)
    {
        return $this->argDefSupport_->getArgDef($index);
    }

    /**
     * @see S2Container_MethodDef::getArgs()
     */
    public function getArgs()
    {
        $args = array();
        $o = $this->getArgDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $args[$i] = $this->getArgDef($i)->getValue();
        }
        return $args;
    }

    /**
     * @see S2Container_MethodDef::getContainer()
     */
    public function getContainer()
    {
        return $this->container_;
    }

    /**
     * @see S2Container_MethodDef::setContainer()
     */
    public function setContainer(S2Container $container)
    {
        $this->container_ = $container;
        $this->argDefSupport_->setContainer($container);
    }

    /**
     * @see S2Container_MethodDef::getExpression()
     */
    public function getExpression()
    {
        return $this->expression_;
    }

    /**
     * @see S2Container_MethodDef::setExpression()
     */
    public function setExpression($expression)
    {
        $this->expression_ = $expression;
    }
}
?>
