<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
final class S2Container_AutoBindingUtil
{
    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     * @param array|ReflectionClass $classes
     * @return boolean 
     */
    public static final function isSuitable($classes)
    {
        if (is_array($classes)) {
            $o = count($classes);
            for ($i = 0; $i < $o; ++$i) {
                if (S2Container_AutoBindingUtil::isSuitable($classes[$i]) === false) {
                    return false;
                }
            }
            return true;
        } else if ($classes instanceof ReflectionClass) {
            if (defined('S2CONTAINER_PHP5_AUTO_DI_INTERFACE') and
                S2CONTAINER_PHP5_AUTO_DI_INTERFACE === true){
                if ($classes->isInterface()) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @return boolean
     */
    public static final function isAuto($mode)
    {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_AUTO)
                == strtolower($mode);
    }
    
    /**
     * @return boolean
     */
    public static final function isConstructor($mode)
    {
        return 
            strtolower(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR)
                == strtolower($mode);
    }
    
    /**
     * @return boolean
     */
    public static final function isProperty($mode)
    {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_PROPERTY)
                == strtolower($mode);
    }
    
    /**
     * @return boolean
     */
    public static final function isNone($mode)
    {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_NONE)
                == strtolower($mode);
    }
}
?>
