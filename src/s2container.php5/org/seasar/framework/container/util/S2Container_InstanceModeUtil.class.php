<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_InstanceModeUtil {

    private function S2Container_InstanceModeUtil() {
    }

    public static final function isSingleton($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_SINGLETON)
                == strtolower($mode);
    }
    
    public static final function isPrototype($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_PROTOTYPE)
                == strtolower($mode);
    }
    
    public static final function isRequest($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_REQUEST)
                == strtolower($mode);
    }
    
    public static final function isSession($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_SESSION)
                == strtolower($mode);
    }
    
    public static final function isOuter($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_OUTER)
                == strtolower($mode);
    }
}
?>