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
// $Id: InstanceModeUtil.class.php,v 1.2 2005/05/31 16:40:22 klove Exp $
/**
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class InstanceModeUtil {

    private function InstanceModeUtil() {
    }

    public static final function isSingleton($mode) {
        return strtolower(ContainerConstants::INSTANCE_SINGLETON)
                == strtolower($mode);
    }
    
    public static final function isPrototype($mode) {
        return strtolower(ContainerConstants::INSTANCE_PROTOTYPE)
                == strtolower($mode);
    }
    
    public static final function isRequest($mode) {
        return strtolower(ContainerConstants::INSTANCE_REQUEST)
                == strtolower($mode);
    }
    
    public static final function isSession($mode) {
        return strtolower(ContainerConstants::INSTANCE_SESSION)
                == strtolower($mode);
    }
    
    public static final function isOuter($mode) {
        return strtolower(ContainerConstants::INSTANCE_OUTER)
                == strtolower($mode);
    }
}
?>