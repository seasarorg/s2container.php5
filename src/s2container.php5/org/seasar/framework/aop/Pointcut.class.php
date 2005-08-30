<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * �v���O�������̂ǂ��ɓ���̃R�[�h(Advice)��}������̂����`���܂��B
 *
 * @package org.seasar.framework.aop
 * @author klove
 */
interface Pointcut { 
    
    /**
     * ���̃��\�b�h��true��Ԃ������\�b�h�ɑ΂���Advice���K�p����܂��B
     *
     * @param string Method Name
     * @return boolean Advice���K�p����邩�ǂ�����Ԃ��܂��B
     */
    public function isApplied($methodName);
}
?>
