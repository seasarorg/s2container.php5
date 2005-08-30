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
 * コンポーネントのメソッドの引数の設定に失敗ときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class IllegalMethodRuntimeException
    extends S2RuntimeException {

    private $componentClass_;
    private $methodName_;

    public function IllegalMethodRuntimeException(
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
