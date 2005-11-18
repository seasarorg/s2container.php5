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
class S2Container_MessageUtil {

    private static $msgMap_ = null;
    
    private function S2Container_MessageUtil() {
    }
    
    /**
     * @param string message id code
     * @params array message words
     */
    public static function getMessageWithArgs($code,$args){
        if(self::$msgMap_ == null){
            self::loadMsgFile();
        }
        if(!is_array($args)){
            return "$args not array.\n";
        }
        if(!is_string($code)){
            return "$code not string.\n";
        }
        
        if(!isset(self::$msgMap_[$code])){
            return "$code not found in " . implode(",", self::$msgMap_) . "\n.";
        }
        $msg = self::$msgMap_[$code];

        $msg = preg_replace('/{/','{$args[',$msg);
        $msg = preg_replace('/}/',']}',$msg);
        $msg = S2Container_EvalUtil::getExpression('"'.$msg.'"');

        return eval($msg);
    }
    
    public static function addMessageResource($resource){
        if(is_readable($resource)){
            //self::$msgMap += parse_ini_file($resource);
            $msg = parse_ini_file($resource);
            self::$msgMap_ = array_merge(self::$msgMap_, $msg);
        } else {
            echo "[ERROR] ${resource} file not found.\n";
        }
    }

    private static function loadMsgFile(){
        if(is_readable(S2CONTAINER_PHP5_MESSAGES_INI)){
            self::$msgMap_ = parse_ini_file(S2CONTAINER_PHP5_MESSAGES_INI);
        }else{
            print "[ERROR] S2CONTAINER_PHP5_MESSAGES_INI file not found.\n";
        }
    }    
}
?>