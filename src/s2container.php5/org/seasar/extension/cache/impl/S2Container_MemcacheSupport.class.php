<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id: $
/**
 * @package org.seasar.framework.cache.impl
 * @author nowel
 */
final class S2Container_MemcacheSupport implements S2Container_CacheSupport {

    // {{{ properties
    /**
     * Container Cache Option
     */
    private $containerOption = array();
    
    /**
     * 
     */
    private $containerInited = false;
    
    /**
     * Aop Cache Option
     */
    private $aopOption = array();
    
    /**
     * 
     */
    private $aopInited = false;
    
    /**
     * S2Container_MemcacheSupport Default Option
     */
    private $defaultOptions = array(
                                'host' => 'localhost',
                                'port' => 11211,
                                'timeout' => null,
                                'cache_compress' => false,
                                'cache_expire' => 0
                            );

    /**
     * memcache instance for Container Cache
     */
    private $memcache4Container = null;
    
    /**
     * memcache instance for Aop Cache
     */
    private $memcache4Aop = null;
    
    /**
     * Logger instance
     */
    private $logger = null;
    // }}}
    
    // {{{ methods
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = S2Container_S2Logger::getLogger(__CLASS__);
        if (defined('S2CONTAINER_PHP5_MEMCACHE_INI')) {
            if (is_readable(S2CONTAINER_PHP5_MEMCACHE_INI)) {
                $ini = parse_ini_file(S2CONTAINER_PHP5_CACHE_LITE_INI, true);
                $this->initialize($ini);
            } else {
                $this->logger->info('can not read ini file. [ ' .
                                    S2CONTAINER_PHP5_MEMCACHE_INI . ' ]',__METHOD__);
            }
        }
        $this->containerOption = $this->defaultOptions;
        $this->aopOption = $this->defaultOptions;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
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
    private function initialize(array $options = null)
    {
        if(isset($options['default'])){
            $this->containerOption = $options['default'];
            $this->aopOption = $options['default'];
            $this->containerInited = true;
            $this->aopInited = true;
        }
        
        if(isset($options['container'])){
            $this->containerOption = array_merge($this->containerOption,
                                                $options['container']);
            $this->containerInited = true;
        }
        
        if(isset($options['aop'])){
            $this->aopOption = array_merge($this->aopOption,
                                            $options['default']);
            $this->aopInited = true;
        }
        
        if($this->containerInited){
            $this->memcache4Container = new Memcache();
            $this->connect($this->memcache4Container, $this->containerOption);
        }
        
        if($this->aopInited){
            $this->memcache4Aop = new Memcache();
            $this->connect($this->memcache4Aop, $this->aopOption);
        }
    }
    
    /**
     * Connection
     * Memcache Connection
     */
    private function connect(Memcache $memcache, $opt)
    {
        if(isset($opt['timeout'])){
            $conn = $memcache->connect($opt['host'], $opt['port'], $opt['timeout']);
        } else {
            $conn = $memcache->connect($opt['host'], $opt['port']);
        }
        if(!$conn){
            throw new Exception('connection failure');
        }
    }
    
    /**
     * 
     */
    public function isContainerCaching($diconPath = null)
    {
        return $this->containerInited;
    }
    
    /**
     * 
     */
    public function isAopProxyCaching($targetClassFile = null)
    {
        return $this->aopInited;
    }

    /**
     * 
     */
    public function loadContainerCache($diconPath)
    {
        if (!$this->containerInited) {
            throw new Exception('container caching not initialized.');
        }
        $keyName = $this->createCacheKeyName($diconPath);
        return $this->get($this->memcache4Container, $keyName);
    }
    
    /**
     * 
     */
    public function loadAopProxyCache($targetClassFile)
    {
        if (!$this->aopInited) {
            throw new Exception('container caching not initialized.');
        }
        $keyName = $this->createCacheKeyName($targetClassFile);
        return $this->get($this->memcache4Aop, $keyName);
    }

    /**
     * 
     */
    public function saveContainerCache($serializedContainer, $diconPath)
    {
        if(is_file($diconPath) && is_readable($diconPath)){
            $keyName = $this->createCacheKeyName($diconPath);
        }
        
        $saved = $this->set($this->memcache4Container,
                            $keyName,
                            $serializedContainer,
                            $this->containerOption);
        if(!$saved){
            throw new Exception('cache write fail.' . $diconPath);
        }
    }

    /**
     */
    public function saveAopProxyCache($srcLine, $targetClassFile)
    {
        if(is_file($targetClassFile) && is_readable($targetClassFile)){
            $keyName = $this->createCacheKeyName($targetClassFile);
        }
        
        $saved = $this->set($this->memcache4Aop,
                            $keyName,
                            $srcLine,
                            $this->aopOption);
        if(!$saved){
            throw new Exception('cache write fail.' . $targetClassFile);
        }
    }

    /**
     * add item to memcached
     * @param $memcahe Memcache instance
     * @param $key key name of item
     * @param $item stored item strings
     * @param $option memcache option
     * @return boolean; true on success
     */
    private function add(Memcache $memcache, $key, $item, $option){
        return $memcache->add($key, $item,
                              $option['cache_compress'],
                              $option['cache_expire']);
    }

    /**
     * set item to memcached
     * @param $memcahe Memcache instance
     * @param $key key name of item
     * @param $item stored item strings
     * @param $option memcache option
     * @return boolean; true on success
     */
    private function set(Memcache $memcache, $key, $item, $option){
        return $memcache->set($key, $item,
                              $option['cache_compress'],
                              $option['cache_expire']);
    }

    /**
     * get item from memcached
     * @param $memcahe Memcache instance
     * @param $key memcached stored item key name
     * @return stored item; if not exits item key from memcached when false
     */
    private function get(Memcache $memcache, $key){
        return $memcache->get($key);
    }

    /**
     * delete item from memcached
     * @param $memcahe Memcache instance
     * @param $key delete item key from memcached
     * @return boolean; true on success
     */
    private function delete(Memcache $memcache, $key){
        return $memcache->delete($key);
    }

    /**
     * replace item of stored item from memcached
     * @param $memcahe Memcache instance
     * @param $key replace item key
     * @param $item replace item
     * @param $option memcache option
     * @return boolean; true on success
     */
    private function replace(Memcache $memcache, $key, $item, $option){
        return $memcache->replace($key, $item,
                                  $option['cache_compress'],
                                  $option['cache_expire']);
    }

    /**
     * create cache name
     * create md5 strings from $item
     * @param $item key value
     * @return string unique key use md5
     */
    private static function createCacheKeyName($item){
        if(is_object($item)){
            return sha1((string)$item);
        }
        return sha1($item);
    }
    // }}}

}

?>