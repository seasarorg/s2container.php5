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
// $Id: S2ContainerMemcacheFactory.class.php 222 2006-03-07 09:58:42Z klove $
/**
 * @package org.seasar.extension.cache
 * @author nowel
 */
final class S2ContainerMemcacheFactory {

    // {{{ properties
    /**
     * host
     */
    private $host = 'localhost';
    
    /**
     * connection port
     */
    private $port = 11211;
    
    /**
     * connection timeout
     */
    private $timeout;

    /**
     * use compression to store object compressed
     */
    private $cache_compress = false;
    
    /**
     *  cache expire timeout; default 0(never expire)
     *  unix timestamp or a number of seconds starting from current
     */
    private $cache_expire = 0;
    
    /**
     * memcache object
     */
    private $memcache = null;
    
    /**
     *
     */
    public static $INITIALIZE_BEFORE_CACHE = false;
    
    /**
     * S2ContainerMemcacheFactory instance 
     */
    public static $INSTANCE = null;
    // }}}
    
    // {{{ methods
    /**
     * Constructor
     */
    private function __construct(){
        $this->memcache = new Memcache();
    }
    
    /**
     * Destructor
     */
    public function __destruct(){
        $this->memcache->close();
        unset($this->memcache);
    }
    
    /**
     * Initialize Options and Connection Memcached
     * $options in array;
     * keys = (
     *          'host' => 'memcached host',
     *          'port' => 'port number',
     *          'timeout' => 'memcached timeout',
     *          'cache_compress' => 'cached compress: boolean',
     *          'cache_expire' => 'expiration time(second time) of the item',
     *        );
     * @param $options set options to this
     */
    public function initialize(array $options = null){
        if($options === null){
            return;
        }
        $this->host = $options['host'];
        
        if(isset($options['port'])){
            $this->port = $options['port'];
        }
        if(isset($options['timeout'])){
            $this->timeout = $options['timeout'];
        }
        if(isset($options['cache_compress'])){
            $this->cache_compress = $options['cache_compress'];
        }
        if(isset($options['cache_expire'])){
            $this->cache_expire = $options['cache_expire'];
        }
    }
    
    /**
     * Connection
     * Memcache Connection
     */
    private function connect(){
        if(isset($this->timeout)){
            $conn = $this->memcache->connect($this->host, $this->port, $this->timeout);
        } else {
            $conn = $this->memcache->connect($this->host, $this->port);
        }
        if(!$conn){
            throw new Exception('connection failure');
        }
    }

    /**
     * Create S2ContainerMemcacheFactory instance(Singletion)
     * @param $options stored items option of memcached; see the self::setOption
     * @return S2ContainerMemcacheFactory
     */
    public static function getInstance(array $options = null){
        if(null === self::$INSTANCE){
            self::$INSTANCE = new self();
        }
        self::$INSTANCE->initialize($options);
        return self::$INSTANCE;
    }

    /**
     * Create S2Container and Cache
     * if available serialized S2Container object in item of memcached;
     *  is done in unserialize and used:
     * if not exists 
     *  when S2Container object is generated, and it stored item in memcached
     * memcached key name of dicon path or optional cacneName
     * @param $diconPath dicon file path
     * @param $cacheName (option)cache key name
     * @return S2Container object
     */
    public static function create($diconPath, $cacheName = null){
        $logger = S2Container_S2Logger::getLogger(__CLASS__);
        $memcache = self::getInstance();
        $memcache->connect();
        if($cacheName == null){
            $cacheName = self::createCacheKeyName($diconPath);
        }
        
        $cache = $memcache->get($cacheName);
        if($cache !== false){
            $container = unserialize($cache);
            $logger->info('cached container available.', __METHOD__);
            if (is_object($container) && $container instanceof S2Container){
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;
            } else {
                throw new Exception('invalid cache found.');
            }
        }

        $logger->info('create container and cache it.', __METHOD__);
        $container = S2ContainerFactory::create($diconPath);

        if(self::$INITIALIZE_BEFORE_CACHE){
            $container->init();
        }

        if(!$memcache->set($cacheName, serialize($container))){
            throw new Exception('cache write fail.');
        }

        return $container;
    }

    /**
     * add item to memcached
     * @param $key key name of item
     * @param $item stored item strings
     * @return boolean; true on success
     */
    private function add($key, $item){
        return $this->memcache->add($key, $item,
                                    $this->cache_compress,
                                    $this->cache_expire);
    }

    /**
     * set item to memcached
     * @param $key key name of item
     * @param $item stored item strings
     * @return boolean; true on success
     */
    private function set($key, $item){
        return $this->memcache->set($key, $item,
                                    $this->cache_compress,
                                    $this->cache_expire);
    }

    /**
     * get item from memcached
     * @param $key memcached stored item key name
     * @return stored item; if not exits item key from memcached when false
     */
    private function get($key){
        return $this->memcache->get($key);
    }

    /**
     * delete item from memcached
     * @param $key delete item key from memcached
     * @return boolean; true on success
     */
    private function delete($key){
        return $this->memcache->delete($key);
    }

    /**
     * replace item of stored item from memcached
     * @param $key replace item key
     * @param $item replace item
     * @return boolean; true on success
     */
    private function replace($key, $item){
        return $this->memcache->replace($key, $item,
                                        $this->cache_compress,
                                        $this->cache_expire);
    }

    /**
     * create cache name
     * create md5 strings from $item
     * @param $item key value
     * @return string unique key use md5
     */
    private static function createCacheKeyName($item){
        if(is_object($item)){
            $name = md5((string)$item);
        } else {
            $name = md5($item);
        }
        return $name;
    }
    // }}}

}

?>