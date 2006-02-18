<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.extension.autoregister.factory
 * @author klove
 */
class S2Container_AnnotationHandlerFactory {

    public static $DEFAULT_ANNOTATION_HANDLER = "S2Container_CommentAnnotationHandler";
    
    private static $annotationHandler = null;
  
    protected function __construct(){
    }
   
    public static function getAnnotationHandler() {
        if(self::$annotationHandler == null){
            if(defined('S2CONTAINER_ANNOTATION_HANDLER')){
                $handler = S2CONTAINER_ANNOTATION_HANDLER;
            }else{
                $handler = self::$DEFAULT_ANNOTATION_HANDLER;
            }
            self::$annotationHandler = new $handler();
        }
        return self::$annotationHandler;  
    }
    
    public static function setAnnotationHandler(S2Continer_AnnotationHandler $handler) {
        self::$annotationHandler = $handler;
    }
}
?>
