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
 * @author klove, nowel
 */
class S2ContainerClassLoader implements S2Loader {
    
    /** */
    const reg_is_phpfile = '/([^\.]+).+php$/';
    /** */
    const directory = DIRECTORY_SEPARATOR;
    
    /**
     * 
     */
    private static $INSTANCE = null;
    /**
     * 
     */
    private $loaders = array();
    /**
     * 
     */
    private $classes = array();
    
    /**
     * 
     */
    private function __construct(){
    }
    
    /**
     * 
     */
    public static function getInstance(){
        if(self::$INSTANCE === null){
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }

    /**
     * ClassLoaderを追加します。
     * 登録できるClassLoaderクラスはS2Loaderを実装する必要があります。
     * @param $loader S2Loaderを実装しているLoaderクラスを登録します。
     * @param $direct 直接spl_autoloadに登録するかを設定島s。
     */
    public static function addLoader(S2Loader $loader, $direct = false){
        if(!$direct){
            return spl_autoload_register(array($loader, '__load'));
        }
        self::getInstance()->__addLoader($loader);
    }
    
    /**
     * 
     */
    private function __addLoader(S2Loader $loader){
        $this->loaders[] = $loader;
    }
    
    /**
     * 
     */
    private function __getLoaders(){
        return $this->loaders;
    }

    /**
     * ClassLoaderを追加します。
     * 登録できるClassLoader関数をautoloaderとして登録します。
     * @param $loader ClassLoaderに登録する関数
     */    
    public static function addLoaderFunction($loader){
        if(function_exists($loader)){
            return spl_autoload_register($loader);
        }
        throw new Exception();
    }
    
    /**
     * 
     */
    public static function __autoload($class){
        $instance = self::getInstance();
        if(!$instance->__load($class)){
            $loaders = $instance->__getLoaders();
            $c = count($loaders);
            for($i = 0; $i < $c; $i++){
                if($loaders[$c]->__load($class)){
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * 
     */
    public function __load($class){
        if(isset($this->classes[$class])){
            $callClass = $this->classes[$class];
            if(file_exists(S2CONTAINER_PHP5 . $callClass)){
                return require_once S2CONTAINER_PHP5 . $callClass;
            }
            return require_once $callClass;
        }
        return false;
    }
    
    /**
     * 
     */
    public static function import($path, $key = null){
        $instance = self::getInstance();
        if(is_array($path) && $key == null){
            $instance->mergeClass($path);
        } else if(is_dir($path) && is_readable($path)) {
            $d = dir($path);
            while (false !== ($entry = $d->read())) {
                if(preg_match(self::reg_is_phpfile, $entry, $m)){
                    $_path = $path . self::directory . $entry;
                    $instance->setClass($m[1], $_path);
                }
            }
            $d->close();
        } else if(is_file($path) && is_readable($path)) {
            if($key == null){
                $file = basename($path);
                if(preg_match(self::reg_is_phpfile, $file, $m)){
                    $instance->setClass($m[1], $path);
                }
            } else {
                $instance->setClass($key, $path);
            }
        } else {
            trigger_error("invalid args. path : $path, key : $key",E_USER_WARNING);
        }
    }
    
    /**
     * 
     */
    public static function addClass($class){
        self::getInstance()->setClass(basename($class), $class);
    }
    
    /**
     * 
     */
    public function setClass($key, $class){
        $this->classes[$key] = $class;
    }
    
    /**
     * 
     */
    private function mergeClass(array $classes){
        $this->classes = array_merge($this->classes, $classes);
    }
    
}

//spl_autoload_register(array('S2ContainerClassLoader', '__autoload'));
 
?>