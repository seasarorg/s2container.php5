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
// $Id: ConstructorUtil.class.php,v 1.2 2005/05/31 16:40:22 klove Exp $
/**
 * @package org.seasar.framework.util
 * @author klove
 */
final class ConstructorUtil {
 
    private function ConstructorUtil() {
    }

    public static function newInstance($refClass,$args,$componentDef=null){
        try {
            
            if($componentDef != null and 
               $componentDef->getAspectDefSize()>0){
               return AopProxyUtil::getEnhancedClass($componentDef,$args); 
            }

            $cmd = "return new " . $refClass->getName() . "(";
            if(count($args) == 0){
                $cmd = $cmd . ");";
                return eval($cmd);
            }
            
            $strArg=array();
            for($i=0;$i<count($args);$i++){
                array_push($strArg,"\$args[" . $i . "]");
            }
            
            $cmd = $cmd . implode(',',$strArg) . ");";
            return eval($cmd);
        }catch(Exception $e){
            throw $e;
        }
    }
}
?>