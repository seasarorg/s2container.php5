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
 * �R���|�[�l���g�̃��\�b�h�̈����̐ݒ�Ɏ��s�Ƃ��̎��s����O
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class S2Container_IllegalMethodRuntimeException
    extends S2Container_S2RuntimeException {

    private $componentClass_;
    private $methodName_;

    public function S2Container_IllegalMethodRuntimeException(
        $componentClass,
        $methodName,
        $cause) {
        parent::__construct("ESSR0060",array($componentClass->getName(), $methodName, $cause),
            $cause);
        $this->componentClass_ = $componentClass;
        $this->methodName_ = $methodName;
    }

    public function getComponentClass() {
        return $this->componentClass_;
    }
    
    public function getMethodName() {
        return $this->methodName_;
    }
}
?>
