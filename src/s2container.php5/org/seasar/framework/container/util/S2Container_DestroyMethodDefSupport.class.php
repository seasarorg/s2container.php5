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
 * DestroyMethodDef�̐ݒ���T�|�[�g���܂��B
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_DestroyMethodDefSupport {

    private $methodDefs_ = array();
    private $container_;

    public function S2Container_DestroyMethodDefSupport() {
    }

    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef) {
        if ($this->container_ != null) {
            $methodDef->setContainer($this->container_);
        }
        array_push($this->methodDefs_,$methodDef);
    }

    public function getDestroyMethodDefSize() {
        return count($this->methodDefs_);
    }

    public function getDestroyMethodDef($index) {
        return $this->methodDefs_[$index];
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getDestroyMethodDefSize(); ++$i) {
            $this->getDestroyMethodDef($i)->setContainer($container);
        }
    }
}
?>