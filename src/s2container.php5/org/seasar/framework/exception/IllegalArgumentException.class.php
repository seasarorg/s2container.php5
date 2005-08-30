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
 * IllegalAccessExceptionをラップする実行時例外です
 * 
 * @package org.seasar.framework.exception
 * @author klove
 */
class IllegalArgumentException extends S2RuntimeException {

    /**
     * @param string message
     */
    public function IllegalArgumentException($cause = null){
        parent::__construct('ESSR1002', array($cause));
    }
}
?>