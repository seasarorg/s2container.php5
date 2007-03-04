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
// $Id: $

/**
 * @package org.seasar.framework.cache.impl
 * @author klove
 */
class S2Container_PearCacheLiteSupport implements S2Container_CacheSupport
{
    private $cacheLite4Container = null;
    private $containerOptions    = array();
    private $containerInited     = false;

    private $cacheLite4AopProxy  = null;
    private $aopProxyOptions     = array();
    private $aopProxyInited      = false;

    public function __construct() {
        if (defined('S2CONTAINER_PHP5_CACHE_LITE_INI')) {
            if (is_readable(S2CONTAINER_PHP5_CACHE_LITE_INI)) {
                $this->initialize();
            } else {
                S2Container_S2Logger::getLogger(__CLASS__)->
                    info('can not read ini file. [ ' . S2CONTAINER_PHP5_CACHE_LITE_INI . ' ]',__METHOD__);
            }
        }
    }

    /**
     * Initialize with ini format file, defined S2CONTAINER_PHP5_CACHE_LITE_INI
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
        $option = parse_ini_file(S2CONTAINER_PHP5_CACHE_LITE_INI, true);
        if (isset($option['default'])) {
            $this->containerOptions = $option['default'];
            $this->aopProxyOptions  = $option['default'];
            $this->containerInited  = true;
            $this->aopProxyInited   = true;
        }

        if (isset($option['container'])) {
            $this->containerInited = true;
            foreach ($option['container'] as $key => $val) {
                $this->containerOptions[$key] = $val;
            }
        }

        if (isset($option['aop'])) {
            $this->aopProxyInited = true;
            foreach ($option['aop'] as $key => $val) {
                $this->aopProxyOptions[$key] = $val;
            }
        }

        if ($this->containerInited) {
            if (isset($this->containerOptions['cacheDir'])) {
                $this->containerOptions['cacheDir'] = 
                    S2Container_StringUtil::expandPath($this->containerOptions['cacheDir']);
            }
            require_once('Cache/Lite.php');
            $this->cacheLite4Container = new Cache_Lite($this->containerOptions);
        }

        if ($this->aopProxyInited) {
            if (isset($this->aopProxyOptions['cacheDir'])) {
                $this->aopProxyOptions['cacheDir'] = 
                    S2Container_StringUtil::expandPath($this->aopProxyOptions['cacheDir']);
            }
            require_once('Cache/Lite.php');
            $this->cacheLite4AopProxy = new Cache_Lite($this->aopProxyOptions);
        }
    }

    /**
     * @see S2Container_CacheSupport::isContainerCaching()
     */
    public function isContainerCaching($diconPath = null) {
        if ($this->containerInited) {
            if (isset($this->containerOptions['caching'])) {
                return $this->containerOptions['caching'];
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
        return $this->cacheLite4Container->get($diconPath);
    }

    /**
     * @see S2Container_CacheSupport::saveContainerCache()
     */
    public function saveContainerCache($serializedContainer,$diconPath) {
        if (!$this->containerInited) {
            throw new Exception('container caching not initialized.');
        }
        return $this->cacheLite4Container->save($serializedContainer,$diconPath);
    }

    /**
     * @see S2Container_CacheSupport::isAopProxyCaching()
     */
    public function isAopProxyCaching($targetClassFile = null) {
        if ($this->aopProxyInited) {
            if (isset($this->aopProxyOptions['caching'])) {
                return $this->aopProxyOptions['caching'];
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
        return $this->cacheLite4AopProxy->get($targetClassFile);
    }

    /**
     * @see S2Container_CacheSupport::saveAopProxyCache()
     */
    public function saveAopProxyCache($srcLine, $targetClassFile) {
        if (!$this->aopProxyInited) {
            throw new Exception('aop proxy caching not initialized.');
        }
        return $this->cacheLite4AopProxy->save($srcLine,$targetClassFile);
    }
}
?>
