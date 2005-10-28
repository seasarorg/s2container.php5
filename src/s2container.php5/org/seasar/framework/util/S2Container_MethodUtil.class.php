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
 * @package org.seasar.framework.util
 * @author klove
 */
final class S2Container_MethodUtil {

    private function S2Container_MethodUtil() {
    }

    public static function invoke($method,$target,$args=null) {
        try {
            if(count($args) == 0){
                return $method->invoke($target,array());
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
     * @param array result of S2Container_ClassUtil::getSource()
     */
    public static function getSource(ReflectionMethod $method,
                                        $src = null) {
        if($src == null){
        	$src = S2Container_ClassUtil::getSource($method->getDeclaringClass());
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