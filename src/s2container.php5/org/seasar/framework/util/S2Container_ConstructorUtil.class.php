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
final class S2Container_ConstructorUtil {
 
    private function S2Container_ConstructorUtil() {
    }

    public static function newInstance($refClass,$args){
/*          
            if($componentDef != null and 
               $componentDef->getAspectDefSize()>0){
               return S2Container_AopProxyUtil::getEnhancedClass($componentDef,$args); 
            }
*/

        if(! $refClass instanceof ReflectionClass){
            throw new S2Container_IllegalArgumentException('args[0] must be <ReflectionClass>');
        }

        $cmd = "return new " . $refClass->getName() . "(";
        if(count($args) == 0){
            $cmd = $cmd . ");";
            return eval($cmd);
        }
            
        $strArg=array();
        $c = count($args);
        for($i=0;$i<$c;$i++){
            array_push($strArg,"\$args[" . $i . "]");
        }
          
        $cmd = $cmd . implode(',',$strArg) . ");";
        return eval($cmd);
    }
}
?>