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
class S2Container_TooManyRegistrationComponentDefImpl 
    extends S2Container_SimpleComponentDef
    implements S2Container_TooManyRegistrationComponentDef
{
    private $key_;
    private $componentDefs_ = array();

    /**
     * @param string key
     */
    public function __construct($key)
    {
        $this->key_ = $key;
    }

    /**
     * @param S2Container_ComponentDef
     */
    public function addComponentDef($componentDef)
    {
        $this->componentDefs_[] = $componentDef;
    }

    /**
     * @return int
     */
    public function getComponentDefSize()
    {
        return count($this->componentDefs_);
    }
    
    /**
     * @see S2Container_ComponentDef::getComponent()
     */
    public function getComponent()
    {
        throw new S2Container_TooManyRegistrationRuntimeException($this->key_,
            $this->getComponentClasses());
    }

    /**
     * @return array 
     */
    public function getComponentDefs()
    {
        return $this->componentDefs_;
    }

    /**
     * @return array
     */
    public function getComponentClasses()
    {
        $classes = array();
        foreach ($this->componentDefs_ as $componentDef) {
            $classes[] = $componentDef->getComponentClass();
        }
        return $classes;
    }
}
?>
