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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.cache
 * @author nowel
 */
final class S2ContainerMemcacheFactory {

    // {{{ properties

    /** host */
    private static $host = 'localhost';
    /** connection port */
    private static $port = 11211;
    /** connection timeout */
    private static $timeout;
    /** memcache object */
    private static $memcache = null;
    /**
     * use compression to store object compressed
     */
    private static $cache_compress = false;
    /**
     *  cache expire timeout; default 0(never expire)
     *  unix timestamp or a number of seconds starting from current
     */
    private static $cache_expire = 0;
    /**
     *
     */
    public static $INITIALIZE_BEFORE_CACHE = false;

    // }}}

    public static function setMemcache(array $prop){
        extract($prop);
        
        self::$host = $host;
        if(isset($port)){
            self::$port = $port;
        }
        if(isset($timeout)){
            self::$timeout = $timeout;
        }
        if(isset($cache_compress)){
            self::$cache_compress = $cache_compress;
        }
        if(isset($cache_expire)){
            self::$cache_expire = $cache_expire;
        }
    }

    public static function connection(){
        if(null === self::$memcache){
            self::$memcache = new Memcache();
            if(isset(self::$timeout)){
                $conn = self::$memcache->connect(self::$host, self::$port, self::$timeout);
            } else {
                $conn = self::$memcache->connect(self::$host, self::$port);
            }
            if(!$conn){
                throw new Exception("connection failure");
            }
        }
        return self::$memcache;
    }

    public function __destruct(){
        self::$memcache->close();
        unset(self::$memcache);
    }

    public static function create($diconPath, $cacheName = null){
        self::connection();
        if($cacheName == null){
            $cacheName = self::getCacheKeyName($diconPath);
        }
        
        $cache = self::get($cacheName);
        if($cache !== false){
            $container = unserialize($cache);
            S2Container_S2Logger::getLogger(__CLASS__)->info("cached container available.", __METHOD__);
            if (is_object($container) && $container instanceof S2Container){
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;
            }else{
                throw new Exception("invalid cache found.");
            }
        }

        S2Container_S2Logger::getLogger(__CLASS__)->info("create container and cache it.", __METHOD__);
        $container = S2ContainerFactory::create($diconPath);

        if(self::$INITIALIZE_BEFORE_CACHE){
            $container->init();
        }

        if(!self::set($cacheName, serialize($container))){
            throw new Exception("cache write fail.");
        }

        return $container;
    }

    protected static function add($key, $item){
        return self::$memcache->add($key, $item, self::$cache_compress, self::$cache_expire);
    }

    protected static function set($key, $item){
        return self::$memcache->set($key, $item, self::$cache_compress, self::$cache_expire);
    }

    protected static function get($key){
        return self::$memcache->get($key);
    }

    protected static function delete($key){
        return self::$memcache->delete($key);
    }

    protected static function replace($key, $item){
        return self::$memcache->replace($key, $item, self::$cache_compress, self::$cache_expire);
    }

    protected static function getCacheKeyName($item){
        if(is_object($item)){
            $name = sha1((string)$item);
        } else {
            $name = sha1($item);
        }
        return $name;
    }

}
