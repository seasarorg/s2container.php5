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
 */
class S2ContainerClassLoader {

    public static $USER_CLASSES = array();

    public static function load($className) {
        if (isset(self::$USER_CLASSES[$className])) {
            require_once(self::$USER_CLASSES[$className]);
            return true;
        } else {
            return false;
       }
    }

    public static function import($path,$key=null) {
        if (is_array($path) && $key == null) {
            self::$USER_CLASSES = array_merge(self::$USER_CLASSES, $path);
        } else if(is_dir($path) and is_readable($path)) {
            $entries = scandir($path);
            if (!$entries) {
                trigger_error("scandir failed. path : $path", E_USER_WARNING);
            } else {           
                foreach ($entries as $entry) {
                    if (preg_match("/([^\.]+).+php$/",$entry,$matches)) {
                        self::$USER_CLASSES[$matches[1]] = $path 
                                                             . DIRECTORY_SEPARATOR
                                                             . $entry;
                    }
                }
            }
        } else if (is_file($path) and is_readable($path)) {
            if ($key == null) {
                $file = basename($path);
                if (preg_match("/([^\.]+).+php$/",$file,$matches)) {
                    self::$USER_CLASSES[$matches[1]] = $path;
                }
            } else {
                self::$USER_CLASSES[$key] = $path;
            }
        } else {
            trigger_error("invalid args. path : $path, key : $key", E_USER_WARNING);
        }
    }
}
?>
