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
 * �v���O�������̂ǂ��ɓ���̃R�[�h(S2Container_Advice)��}������̂����`���܂��B
 *
 * @package org.seasar.framework.aop
 * @author klove
 */
interface S2Container_Pointcut { 
    
    /**
     * ���̃��\�b�h��true��Ԃ������\�b�h�ɑ΂���Advice���K�p����܂��B
     *
     * @param string Method Name
     * @return boolean Advice���K�p����邩�ǂ�����Ԃ��܂��B
     */
    public function isApplied($methodName);
}
?>