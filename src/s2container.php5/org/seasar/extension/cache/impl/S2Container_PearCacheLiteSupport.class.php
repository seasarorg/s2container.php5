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
// $Id: $

require_once('Cache/Lite.php');

/**
 * @package org.seasar.framework.cache.impl
 * @author klove
 */
class S2Container_PearCacheLiteSupport implements S2Container_CacheSupport
{

    public static $CONTAINER_OPTIONS = null;
    public static $AOP_PROXY_OPTIONS = null;
    private $cacheLite4Container = null;
    private $cacheLite4AopProxy = null;

    public function init() {
        $this->cacheLite4Container = null;
        $this->cacheLite4AopProxy = null;
    }
    
    public function isContainerCaching($diconPath = null) {
        if (is_array(self::$CONTAINER_OPTIONS)) {
            if (isset(self::$CONTAINER_OPTIONS['caching'])) {
                return self::$CONTAINER_OPTIONS['caching'];
            }
            return true;
        } 
        else {
            return false;
        }
    }

    public function loadContainerCache($diconPath) {
        $cacheLite = $this->getCacheLite4Container();
        return $cacheLite->get($diconPath);
    }

    public function saveContainerCache($serializedContainer,$diconPath) {
        $cacheLite = $this->getCacheLite4Container();
        return $cacheLite->save($serializedContainer,$diconPath);
    }

    public function isAopProxyCaching($targetClassFile = null) {
        if (is_array(self::$AOP_PROXY_OPTIONS)) {
            if (isset(self::$AOP_PROXY_OPTIONS['caching'])) {
                return self::$AOP_PROXY_OPTIONS['caching'];
            }
            return true;
        } 
        else {
            return false;
        }
    }

    public function loadAopProxyCache($targetClassFile) {
        $cacheLite = $this->getCacheLite4AopProxy();
        return $cacheLite->get($targetClassFile);
    }

    public function saveAopProxyCache($srcLine, $targetClassFile) {
        $cacheLite = $this->getCacheLite4AopProxy();
        return $cacheLite->save($srcLine,$targetClassFile);
    }

    private function getCacheLite4Container() {
        if (is_null($this->cacheLite4Container)) {
            $this->cacheLite4Container = new Cache_Lite(self::$CONTAINER_OPTIONS);
        }
        return $this->cacheLite4Container;
    }

    private function getCacheLite4AopProxy() {
        if (is_null($this->cacheLite4AopProxy)) {
            $this->cacheLite4AopProxy = new Cache_Lite(self::$AOP_PROXY_OPTIONS);
        }
        return $this->cacheLite4AopProxy;
    }
}
?>
