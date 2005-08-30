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
// $Id: InstantiationException.class.php,v 1.1 2005/05/28 16:50:13 klove Exp $
/**
 * InstantiationExceptionをラップする実行時例外です。
 * 
 * @package org.seasar.framework.exception
 * @author klove
 */
class InstantiationException extends S2RuntimeException{

    function InstantiationException() {
        parent::__construct('ESSR1004',array());
    }
}
?>