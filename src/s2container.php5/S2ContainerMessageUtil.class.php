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
// |          nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @author klove
 *         nowel
 */
class S2ContainerMessageUtil {

    private static $msgMap_ = array();
    
    private function __construct() {
    }
    
    /**
     * @param string message id code
     * @params array message words
     */
    public static function getMessageWithArgs($code,$args){
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

        if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') and S2CONTAINER_PHP5_DEBUG_EVAL){
            S2Container_S2Logger::getLogger(__CLASS__)->
                debug("[ $msg ]",__METHOD__);
        }

        return eval($msg);
    }
    
    public static function addMessageResource($resource){
        if(is_readable($resource)){
            $msg = parse_ini_file($resource);
            self::$msgMap_ = array_merge(self::$msgMap_, $msg);
        } else {
            S2Container_S2Logger::getLogger(__CLASS__)->
                warn("$resource file not found.",__METHOD__);
        }
    }
}
?>
