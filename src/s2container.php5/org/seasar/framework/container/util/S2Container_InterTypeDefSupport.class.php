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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id: S2Container_AutoBindingUtil.class.php 225 2006-03-07 13:34:12Z klove $
/**
 * @package org.seasar.framework.container.util
 * @author nowel
 */
class S2Container_InterTypeDefSupport {

    /** */
    private $interTypeDefs = array();
    /** */
    private $container;

    /**
     * 
     */
    public function __construct() {
    }

    /**
     * 
     */
    public function addInterTypeDef(S2Container_InterTypeDef $interTypeDef) {
        if ($this->container !== null) {
            $interTypeDef->setContainer($this->container);
        }
        $this->interTypeDefs[] = $interTypeDef;
    }

    /**
     * 
     */
    public function getInterTypeDefSize() {
        return count($this->interTypeDefs);
    }

    /**
     * 
     */
    public function getInterTypeDef($index) {
        return $this->interTypeDefs[$index];
    }

    /**
     * 
     */
    public function setContainer(S2Container $container) {
        $this->container = $container;
        $c = $this->getInterTypeDefSize();
        for ($i = 0; $i < $c; ++$i) {
            $this->getInterTypeDef($i)->setContainer($container);
        }
    }
}

?>