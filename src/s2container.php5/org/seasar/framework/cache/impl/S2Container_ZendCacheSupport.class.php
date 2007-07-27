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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$

/**
 * @package org.seasar.framework.cache.impl
 * @author klove
 */
class S2Container_ZendCacheSupport implements S2Container_CacheSupport
{
    private $zendCache4Container = null;
    private $containerOptions    = array();
    private $containerInited     = false;

    private $zendCache4AopProxy  = null;
    private $aopOptions     = array();
    private $aopProxyInited      = false;

    public function __construct() {
        if (defined('S2CONTAINER_PHP5_ZEND_CACHE_INI')) {
            if (is_readable(S2CONTAINER_PHP5_ZEND_CACHE_INI)) {
                $this->initialize();
            } else {
                S2Container_S2Logger::getLogger(__CLASS__)->
                    info('can not read ini file. [ ' . S2CONTAINER_PHP5_ZEND_CACHE_INI . ' ]',__METHOD__);
            }
        }
    }

    /**
     * Initialize with ini format file, defined S2CONTAINER_PHP5_ZEND_CACHE_INI
     * 
     * INI format sections
     *   [default]
     *     defined options are applied to both [container] and [aop] as default value.  
     *   [container]
     *     for container options.
     *   [aop]
     *     for aop options.
     * 
     * Available options
     *   @see http://pear.php.net/manual/ja/package.caching.cache-lite.cache-lite.cache-lite.php
     * 
     * Example
     *   [default]
     *   cacheDir = "/tmp"
     * 
     *   [container]
     *   caching = "true"
     *   lifeTime = "3600"
     * 
     *   [aop]
     *   caching = "false"
     *   lifeTime = "60"
     * 
     */
    private function initialize() {
        require_once('Zend/Config/Ini.php');
        $option = new Zend_Config_Ini(S2CONTAINER_PHP5_ZEND_CACHE_INI, null);

        if (isset($option->container)) {
            $this->containerInited = true;
            if (isset($option->container->frontend) and 
                isset($option->container->frontend->name) and
                isset($option->container->backend) and
                isset($option->container->backend->name)) {
                $this->containerOptions['frontend'] = $option->container->frontend->toArray();
                $this->containerOptions['frontend_name'] = $this->containerOptions['frontend']['name'];
                unset($this->containerOptions['frontend']['name']);
                $this->containerOptions['backend'] = $option->container->backend->toArray();
                $this->containerOptions['backend_name'] = $this->containerOptions['backend']['name'];
                unset($this->containerOptions['backend']['name']);
            } else {
                throw new Exception('invalid container options.');
            }
        }

        if (isset($option->aop)) {
            $this->aopProxyInited = true;
            if (isset($option->aop->frontend) and
                isset($option->aop->frontend->name) and
                isset($option->aop->backend) and
                isset($option->aop->backend->name)) {
                $this->aopOptions['frontend'] = $option->aop->frontend->toArray();
                $this->aopOptions['frontend_name'] = $this->aopOptions['frontend']['name'];
                unset($this->aopOptions['frontend']['name']);
                $this->aopOptions['backend'] = $option->aop->backend->toArray();
                $this->aopOptions['backend_name'] = $this->aopOptions['backend']['name'];
                unset($this->aopOptions['backend']['name']);
            } else {
                throw new Exception('invalid aop options.');
            }
        }

        if ($this->containerInited) {
            if (isset($this->containerOptions['backend']['cache_dir'])) {
                $this->containerOptions['backend']['cache_dir'] = 
                    S2Container_StringUtil::expandPath($this->containerOptions['backend']['cache_dir']);
            }
            if (isset($this->containerOptions['frontend']['caching'])) {
                $this->containerOptions['frontend']['caching'] = strtolower($this->containerOptions['frontend']['caching']) === 'true' ? true : false;
            }
            require_once('Zend/Cache.php');
            $this->zendCache4Container = Zend_Cache::factory(
                                             $this->containerOptions['frontend_name'],
                                             $this->containerOptions['backend_name'],
                                             $this->containerOptions['frontend'],
                                             $this->containerOptions['backend']);
        }

        if ($this->aopProxyInited) {
            if (isset($this->aopOptions['backend']['cache_dir'])) {
                $this->aopOptions['backend']['cache_dir'] = 
                    S2Container_StringUtil::expandPath($this->aopOptions['backend']['cache_dir']);
            }
            if (isset($this->aopOptions['frontend']['caching'])) {
                $this->aopOptions['frontend']['caching'] = strtolower($this->aopOptions['frontend']['caching']) === 'true' ? true : false;
            }
            require_once('Zend/Cache.php');
            $this->zendCache4AopProxy = Zend_Cache::factory(
                                             $this->aopOptions['frontend_name'],
                                             $this->aopOptions['backend_name'],
                                             $this->aopOptions['frontend'],
                                             $this->aopOptions['backend']);
        }
    }

    /**
     * @see S2Container_CacheSupport::isContainerCaching()
     */
    public function isContainerCaching($diconPath = null) {
        if ($this->containerInited) {
            if (isset($this->containerOptions['frontend']['caching'])) {
                return $this->containerOptions['frontend']['caching'];
            }
            return true;
        } 
        else {
            return false;
        }
    }

    /**
     * @see S2Container_CacheSupport::loadContainerCache()
     */
    public function loadContainerCache($diconPath) {
        if (!$this->containerInited) {
            throw new Exception('container caching not initialized.');
        }
        return $this->zendCache4Container->load(sha1($diconPath));
    }

    /**
     * @see S2Container_CacheSupport::saveContainerCache()
     */
    public function saveContainerCache($serializedContainer,$diconPath) {
        if (!$this->containerInited) {
            throw new Exception('container caching not initialized.');
        }
        return $this->zendCache4Container->save($serializedContainer, sha1($diconPath));
    }

    /**
     * @see S2Container_CacheSupport::isAopProxyCaching()
     */
    public function isAopProxyCaching($targetClassFile = null) {
        if ($this->aopProxyInited) {
            if (isset($this->aopOptions['frontend']['caching'])) {
                return $this->aopOptions['frontend']['caching'];
            }
            return true;
        } 
        else {
            return false;
        }
    }

    /**
     * @see S2Container_CacheSupport::loadAopProxyCache()
     */
    public function loadAopProxyCache($targetClassFile) {
        if (!$this->aopProxyInited) {
            throw new Exception('aop proxy caching not initialized.');
        }
        return $this->zendCache4AopProxy->load(sha1($targetClassFile));
    }

    /**
     * @see S2Container_CacheSupport::saveAopProxyCache()
     */
    public function saveAopProxyCache($srcLine, $targetClassFile) {
        if (!$this->aopProxyInited) {
            throw new Exception('aop proxy caching not initialized.');
        }
        return $this->zendCache4AopProxy->save($srcLine, sha1($targetClassFile));
    }
}
?>
