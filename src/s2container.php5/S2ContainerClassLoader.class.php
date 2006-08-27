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
 * Usage:
 * <code>S2ContainerClassLoader::addClassLoader(new S2ContainerClassLoader); </code>
 * @author klove, nowel
 */
class S2ContainerClassLoader implements S2ClassLoader {
    
    /** phpファイルであることを判別する正規表現 */
    const reg_is_phpfile = '/([^\.]+).+php$/';
    /** ディレクトリパスを示す定数 */
    const directory = DIRECTORY_SEPARATOR;
    
    /**
     * 自分自身のインスタンスを保持します。
     */
    private static $INSTANCE = null;
    /**
     * 登録しているLoader
     */
    private $loaders = array();
    /**
     * 保持しているLoad対象になるphpクラス
     */
    private $classes = array();
    
    /**
     * コンストラクタです。
     */
    private function __construct(){
    }
    
    /**
     * 自分自身のインスタンスを返します。
     * @return S2ContainerClassLoader singelonのS2ContainerClassLoader
     */
    public static function getInstance(){
        if(self::$INSTANCE === null){
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }

    /**
     * Loaderを追加します。
     * 登録できるLoaderはS2Loaderを実装する必要があります。
     * @param $loader S2Loaderを実装しているLoaderクラスを追加します。
     */
    public static function addLoader(S2Loader $loader){
        self::getInstance()->__addLoader($loader);
    }
    
    /**
     * ClassLoaderを追加します。
     * 登録できるClassLoaderはS2ClassLoaderを実装している必要があります。
     * @param $loader S2ClassLoaderを実装しているClassLoaderを追加します。
     */
    public static function addClassLoader(S2ClassLoader $loader){
        return spl_autoload_register(array($loader, '__load'));
    }
    
    /**
     * Loaderを追加します。
     * @param S2Loader Loaderオブジェクト
     */
    private function __addLoader(S2Loader $loader){
        $this->loaders[] = $loader;
    }
    
    /**
     * 保持しているLoaderを返します。
     * @return array 保持しているLoaderオブジェクトの配列
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
     * クラスが呼ばれた際に登録されているLoaderを呼び出します。
     * 最初はS2ContainerClassLoaderに登録されているクラスを探します。
     * その後登録されているLoaderを順次呼び出します。
     * @param $class 呼び出されたClass
     * @return loadに成功した場合true, loaderにクラスが無い場合false
     */
    public static function __load($class){
        $instance = self::getInstance();
        if(!$instance->load($class)){
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
     * 登録されているphpクラスを<code>require_once</code>します。
     * もしphpクラスが登録されていない場合はfalseを返します。
     * @param $class <code>require_once</code>
     * @return boolean <code>require_once</code>に成功した場合true;
     *                  phpクラスが登録されていない場合false
     */
    public function load($class){
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

?>
