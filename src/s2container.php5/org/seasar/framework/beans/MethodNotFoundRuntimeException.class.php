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
 * 対象のクラスに適用可能なメソッドが見つからなかった場合の実行時例外です。
 * 
 * @package org.seasar.framework.beans
 * @author klove
 */
final class MethodNotFoundRuntimeException extends S2RuntimeException {

    private $targetClass_;
    private $methodName_;
    private $methodArgClasses_;

    public function MethodNotFoundRuntimeException(
        $targetClass,
        $methodName,
        $methodArgs) {

        parent::__construct(
            "ESSR0049",array($targetClass->getName(),$methodName));
        $this->targetClass_ = $targetClass;
        $this->methodName_ = $methodName;
    }

    public function getTargetClass() {
        return $this->targetClass_;
    }

    public function getMethodName() {
        return $this->methodName_;
    }

    public function getMethodArgClasses() {
        return $this->methodArgClasses_;
    }

}
?>
