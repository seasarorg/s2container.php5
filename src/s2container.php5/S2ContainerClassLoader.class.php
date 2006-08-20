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
interface S2Loader {
    public static function __load($class);
}
 
class S2ContainerClassLoader implements S2Loader {
    static $CLASSES = array();
    
    /**
     * ClassLoaderを追加します。
     * 登録できるClassLoaderクラスはS2Loaderを実装する必要があります。
     * @param $loader S2Loaderを実装しているLoaderクラスを登録します。
     */
    public static function addLoader(S2Loader $loader){
        return spl_autoload_register(array($loader, '__load'));
    }
    
    /**
     * ClassLoaderを追加します。
     * 登録できるClassLoader関数をautoloaderとして登録します。
     * @param $loader ClassLoaderに登録する関数
     */
    public static function addLoaderFunction($loader){
        return spl_autoload_register($loader);
    }

    public static function __load($className){
        if(array_key_exists($className,self::$CLASSES)){
            require_once(S2CONTAINER_PHP5 . self::$CLASSES[$className]);
            return true;
        } else if(isset(self::$USER_CLASSES[$className])){
            require_once(self::$USER_CLASSES[$className]);
            return true;
        }
        return false;
    }

    static $USER_CLASSES = array();
    public static function import($path,$key=null){
        if(is_array($path) && $key == null){
            self::$USER_CLASSES = array_merge(self::$USER_CLASSES, $path);
        } else if(is_dir($path) && is_readable($path)) {
            $d = dir($path);
            while (false !== ($entry = $d->read())) {
                if(preg_match("/([^\.]+).+php$/",$entry,$matches)){
                    S2ContainerClassLoader::$USER_CLASSES[$matches[1]] = "$path/$entry";
                }
            }
            $d->close();
        } else if(is_file($path) && is_readable($path)) {
            if($key == null){
                $file = basename($path);
                if(preg_match("/([^\.]+).+php$/",$file,$matches)){
                    S2ContainerClassLoader::$USER_CLASSES[$matches[1]] = $path;
                }
            } else {
                S2ContainerClassLoader::$USER_CLASSES[$key] = $path;
            }
        } else {
            trigger_error("invalid args. path : $path, key : $key",E_USER_WARNING);
        }
    }
}

S2ContainerClassLoader::addLoader('S2ContainerClassLoader');
?>
