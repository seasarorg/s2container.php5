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
 * @package org.seasar.framework.util
 * @author klove
 */
final class StringUtil {

    private function StringUtil() {
    }
    
    /**
     * @param string
     */
    public static function expandPath($path){
        
        if(preg_match('/^\%([0-9a-zA-Z_]+)\%(.*)/',$path,$regs)){
            $prefix = $regs[1];
            $expand = "return " . $regs[1] . ". '" . $regs[2] . "';";
            $path = eval($expand);
        }    
        
        return $path;
    } 
}
?>