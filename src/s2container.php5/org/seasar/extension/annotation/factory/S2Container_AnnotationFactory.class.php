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
 * @package org.seasar.extension.annotation.factory
 * @author klove
 */
final class S2Container_AnnotationFactory {

    const ARGS_TYPE_ARRAY = 1;
    const ARGS_TYPE_HASH = 2;
    
    private function __construct() {}
    
    public static function create($annotationType,
                                  $args = array(),
                                  $argType = self::ARGS_TYPE_ARRAY){

        if(class_exists($annotationType)){
            $annoObj= new $annotationType();        
        }else{
            return null;    
        }
 
        if(count($args) == 0){
            return $annoObj;
        }
        
        if($argType == self::ARGS_TYPE_ARRAY){
            if(count($args)==1){
                $annoObj->value = $args[0];
            }else{
                $annoObj->value = $args;
            }
            return $annoObj;
        }

        foreach($args as $arg=>$val){
            if(!array_key_exists($arg,$annoObj)){
                $className = get_class($annoObj);
                S2Container_S2Logger::getLogger(__CLASS__)->
                    info("class : <$className> property <$arg> should be define as public.",__METHOD__);
            }

            $annoObj->$arg = $val;
        }
        return $annoObj;
    }
}
?>
