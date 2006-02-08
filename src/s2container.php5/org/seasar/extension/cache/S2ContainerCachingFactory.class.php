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
 * @package org.seasar.extension.cache
 * @author klove
 */
final class S2ContainerCachingFactory
{
    public static $INITIALIZE_BEFORE_CACHE = false;
    /**
     * @param string dicon path 
     * @param string cache file name 
     */
    public static function create($diconPath,$cacheName = null) 
    {
        if (!self::_isCacheDirectoryAvailable()){
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("cache directory not available.",__METHOD__);
            return S2ContainerFactory::create($diconPath);
        }

        $cacheFilePath = self::_getCacheFilePath($diconPath,$cacheName);
        
        if (self::_isValidCache($cacheFilePath,$diconPath)){
            $container = unserialize(file_get_contents($cacheFilePath));
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("cached container available.",__METHOD__);
            if (is_object($container) and 
                $container instanceof S2Container){
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;    
            }else{
                throw new Exception("invalid cache found.");
            }
        }
        
        S2Container_S2Logger::getLogger(__CLASS__)->
            info("create container and cache it.",__METHOD__);
        $container = S2ContainerFactory::create($diconPath);

        if(self::$INITIALIZE_BEFORE_CACHE){
           $container->init(); 
        }

        if(!file_put_contents($cacheFilePath,
                             serialize($container),
                             LOCK_EX)){
                throw new Exception("cache write fail.");
        }
        return $container;
    }

    /**
     * 
     */  
    private static function _isCacheDirectoryAvailable(){
        if (defined('S2CONTAINER_PHP5_CACHE_DIR') and
            is_dir(S2CONTAINER_PHP5_CACHE_DIR) and 
            is_writable(S2CONTAINER_PHP5_CACHE_DIR)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 
     */
    private static function _getCacheFilePath($path,$cacheName){
        if($cacheName != null){
            return S2CONTAINER_PHP5_CACHE_DIR . DIRECTORY_SEPARATOR . $cacheName;
        }else{
            return S2CONTAINER_PHP5_CACHE_DIR . DIRECTORY_SEPARATOR . md5($path);
        }
    }
    
    /**
     * 
     */
   private static function _isValidCache($cacheFilePath,$diconPath){
    
       if (is_file($cacheFilePath) and 
           is_readable($cacheFilePath) and
           filemtime($cacheFilePath) > filemtime($diconPath)){
           return true;
       }else{
           return false; 
       }
   }
}
?>
