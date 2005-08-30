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
// $Id: IllegalConstructorRuntimeException.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * コンポーネントのコンストラクタ引数の設定に失敗ときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class IllegalConstructorRuntimeException
    extends S2RuntimeException {

    private $componentClass_;

    public function IllegalConstructorRuntimeException(
        $componentClass,$cause) {
        parent::__construct("ESSR0058",array($componentClass->getName(), $cause ),$cause);
        $this->componentClass_ = $componentClass;
    }

    public function getComponentClass() {
        return $this->componentClass_;
    }
}
?>
