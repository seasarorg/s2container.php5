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
 * 1�̃L�[�ɕ����̃R���|�[�l���g���o�^���ꂽ�ꍇ�Ɏg�p����܂��B
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface TooManyRegistrationComponentDef {
    public function addComponentDef($componentDef);
    public function getComponentClasses();
}
?>
