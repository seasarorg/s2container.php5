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
 * 定義されたコンポーネントのクラスと実際のコンポーネントのクラスが異なるときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class S2Container_ClassUnmatchRuntimeException extends S2Container_S2RuntimeException {

    private $componentClass_;
    private $realComponentClass_;
    
    public function S2Container_ClassUnmatchRuntimeException(
        $componentClass,
        $realComponentClass) {
        parent::__construct("ESSR0069", 
            array(
            $componentClass != null ? $componentClass->getName() : "null",
            $realComponentClass != null ? $realComponentClass->getName() : "null"));
            
        $this->componentClass_ = $componentClass;
        $this->realComponentClass_ = $realComponentClass;
    }
    
    public function getComponentClass() {
        return $ths->componentClass_;
    }
    
    public function getRealComponentClass() {
        return $this->realComponentClass_;
    }
}
?>
