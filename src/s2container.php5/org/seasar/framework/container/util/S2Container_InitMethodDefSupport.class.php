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
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_InitMethodDefSupport
{
    private $methodDefs_ = array();
    private $container_;

    /**
     * 
     */
    public function __construct()
    {
    }

    /**
     * @param S2Container_InitMethodDef
     */
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef)
    {
        if ($this->container_ != null) {
            $methodDef->setContainer($this->container_);
        }
        $this->methodDefs_[] = $methodDef;
    }

    /**
     * @return int
     */
    public function getInitMethodDefSize()
    {
        return count($this->methodDefs_);
    }

    /**
     * @param int
     * @return S2Container_InitMethodDef
     */
    public function getInitMethodDef($index)
    {
        return $this->methodDefs_[$index];
    }

    /**
     * @param S2Container
     */
    public function setContainer(S2Container $container)
    {
        $this->container_ = $container;
        $o = $this->getInitMethodDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $this->getInitMethodDef($i)->setContainer($container);
        }
    }
}
?>
