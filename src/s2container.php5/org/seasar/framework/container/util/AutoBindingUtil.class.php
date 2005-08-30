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
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class AutoBindingUtil {

    private function AutoBindingUtil() {
    }

    public static final function isSuitable($classes) {
        
        if(is_array($classes)){
            for ($i = 0; $i < count($classes); ++$i) {
                if (!AutoBindingUtil::isSuitable($classes[$i])) {
                    return false;
                }
            }
        }else{
            if($classes != null){
                return $classes->isInterface();
            }else{
                return false;
            }
        }
        return true;
    }
    
    public static final function isAuto($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_AUTO)
                == strtolower($mode);
    }
    
    public static final function isConstructor($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_CONSTRUCTOR)
                == strtolower($mode);
    }
    
    public static final function isProperty($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_PROPERTY)
                == strtolower($mode);
    }
    
    public static final function isNone($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_NONE)
                == strtolower($mode);
    }
}
?>