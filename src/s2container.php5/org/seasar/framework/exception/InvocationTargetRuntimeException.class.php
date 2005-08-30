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
 * InvocationTargetExceptionをラップする実行時例外です。
 * 
 * @package org.seasar.framework.exception
 * @author klove
 */
class InvocationTargetRuntimeException extends S2RuntimeException {

    private $targetClass_;

    public function InvocationTargetRuntimeException(
        $targetClass = null,
        $cause = null) {

        parent::__construct(
            "ESSR0043",
            array($targetClass != null ? $targetClass->getName() : "null",
                   $cause != null ? $cause->getTargetException() : "null"),
            $cause->getTargetException());
        $this->targetClass_ = $targetClass;
    }
    
    public function getTargetClass() {
        return $this->targetClass_;
    }
}
?>