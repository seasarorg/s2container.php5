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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_MetaDefImpl
    extends S2Container_ArgDefImpl
    implements S2Container_MetaDef
{
    private $name_;
 
    /**
     * @param string 
     * @param value
     */   
    public function __construct($name = null,$value = null)
    {
        parent::__construct($value);
        
        if ($name != null) {
            $this->name_ = $name;
        }
    }

    /**
     * @see S2Container_MetaDef::getName()
     */    
    public function getName()
    {
        return $this->name_;
    }
}
?>
