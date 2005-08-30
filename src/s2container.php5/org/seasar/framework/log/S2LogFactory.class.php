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
// $Id: S2LogFactory.class.php,v 1.1 2005/05/28 16:50:13 klove Exp $
/**
 * @package org.seasar.framework.log
 * @author klove
 */
class S2LogFactory {

    private function LogFactory() {
    }
    
    public static function getLog($className){
        return new SimpleLogger($className);
    }
}
?>