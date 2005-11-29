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
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_ArgDefSupport
{
    private $argDefs_ = array();
    private $container_;
    
    /**
     * 
     */
    public function __construct()
    {
    }

    /**
     * @param S2Container_ArgDef
     */
    public function addArgDef(S2Container_ArgDef $argDef)
    {
        if ($this->container_ != null) {
            $argDef->setContainer($this->container_);
        }
        array_push($this->argDefs_,$argDef);
    }
    
    /**
     * @return int
     */
    public function getArgDefSize()
    {
        return count($this->argDefs_);
    }
    
    /**
     * @param int
     * @return S2Container_ArgDef
     */
    public function getArgDef($index)
    {
        return $this->argDefs_[$index];
    }
  
    /**
     * @param S2Container
     */  
    public function setContainer(S2Container $container)
    {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getArgDefSize(); $i++) {
            $this->getArgDef($i)->setContainer($container);
        }
    }
}
?>
