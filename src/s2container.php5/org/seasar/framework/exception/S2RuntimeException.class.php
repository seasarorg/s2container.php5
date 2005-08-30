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
// $Id: S2RuntimeException.class.php,v 1.2 2005/06/21 16:33:46 klove Exp $
/**
 * @package org.seasar.framework.exception
 * @author klove
 */
class S2RuntimeException extends Exception {

    private $messageCode_;
    private $args_;
    private $message_;
    private $simpleMessage_;

    public function S2RuntimeException(
        $messageCode,
        $args = null,
        $cause = null) {

        $cause instanceof Exception ? 
            $msg = $cause->getMessage() . "\n" :
            $msg = "";
        $msg .= MessageUtil::getMessageWithArgs($messageCode,$args);
        parent::__construct($msg);
    }
}
?>