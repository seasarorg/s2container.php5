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
 * コンポーネントの循環参照が起きたときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class S2Container_CyclicReferenceRuntimeException extends S2Container_S2RuntimeException {

    private $componentClass_;
    
    public function S2Container_CyclicReferenceRuntimeException(
        $componentClass) {
        parent::__construct("ESSR0047",array($componentClass->getName()));
            
        $this->componentClass_ = $componentClass;
    }
    
    public function getComponentClass() {
        return $this->componentClass_;
    }
}
?>
