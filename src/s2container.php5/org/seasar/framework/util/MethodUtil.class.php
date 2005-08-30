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
// $Id: MethodUtil.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.util
 * @author klove
 */
final class MethodUtil {

    private function MethodUtil() {
    }

    public static function invoke($method,$target,$args) {
        try {
            if(!is_array($args)){
                $args = array($args);
            }
            if(count($args) == 0){
                return $method->invoke($target,$args);
            }

            $strArg=array();
            for($i=0;$i<count($args);$i++){
                array_push($strArg,"\$args[" . $i . "]");
            }
            $methodName = $method->getName();
            $cmd = 'return $target->' . $methodName . '('.
                   implode(',',$strArg) . ");";
            return eval($cmd);
        }catch(Exception $e){
            throw $e;
        }
    }
    
    public static function isAbstract(ReflectionMethod $method) {
        return $method->isAbstract();
    }

    /**
     * @param ReflectionMethod method
     * @param array result of ClassUtil::getSource()
     */
    public static function getSource(ReflectionMethod $method,
                                        $src = null) {
        if($src == null){
        	$src = ClassUtil::getSource($method->getDeclaringClass());
        }
        
        $def = array();
        $start = $method->getStartLine();
        $end = $method->getEndLine();

        for($i=$start-1;$i<$end;$i++){
            array_push($def,$src[$i]);
        }
        
        return $def;
    }
}
?>