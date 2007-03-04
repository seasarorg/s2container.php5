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
class S2Container_FileCacheSupport implements S2Container_CacheSupport
{
    /**
     * @see S2Container_CacheSupport::isContainerCaching()
     */
    public function isContainerCaching($diconPath = null) {
        if (defined('S2CONTAINER_PHP5_CACHE_DIR') and
            is_dir(S2CONTAINER_PHP5_CACHE_DIR) and 
            is_writable(S2CONTAINER_PHP5_CACHE_DIR)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @see S2Container_CacheSupport::loadContainerCache()
     */
    public function loadContainerCache($diconPath) {
        $cacheFilePath = $this->getContainerCacheFilePath($diconPath);
        if (is_file($cacheFilePath) and 
            is_readable($cacheFilePath) and
            filemtime($cacheFilePath) > filemtime($diconPath)) {
            return file_get_contents($cacheFilePath);
        }
        else {
            return false;
        }
    }

    /**
     * @see S2Container_CacheSupport::saveContainerCache()
     */
    public function saveContainerCache($serializedContainer,$diconPath) {
        if (!file_put_contents($this->getContainerCacheFilePath($diconPath), $serializedContainer, LOCK_EX)) {
            throw new Exception('cache write fail. [ ' . $this->getContainerCacheFilePath($diconPath) . ' ]');
        }
    }

    private function getContainerCacheFilePath($path) {
        return S2CONTAINER_PHP5_CACHE_DIR
             . DIRECTORY_SEPARATOR
             . sha1($path)
             . '.dicon';
    }


    /**
     * @see S2Container_CacheSupport::isAopProxyCaching()
     */
    public function isAopProxyCaching($targetClassFile = null) {
        if (defined('S2AOP_PHP5_FILE_CACHE') and
           S2AOP_PHP5_FILE_CACHE and
           defined('S2AOP_PHP5_FILE_CACHE_DIR') and
           is_dir(S2AOP_PHP5_FILE_CACHE_DIR) and
           is_writable(S2AOP_PHP5_FILE_CACHE_DIR)) {
            return true;
        }
        return false;
    }

    /**
     * @see S2Container_CacheSupport::loadAopProxyCache()
     */
    public function loadAopProxyCache($targetClassFile) {
        $cacheFile = $this->getAopProxyCacheFilePath($targetClassFile);
        if (is_readable($cacheFile) and
            filemtime($targetClassFile) < filemtime($cacheFile)) {
            return file_get_contents($cacheFile);
        }
        return false;
    }

    /**
     * @see S2Container_CacheSupport::saveAopProxyCache()
     */
    public function saveAopProxyCache($srcLine, $targetClassFile) {
        if (!file_put_contents($this->getAopProxyCacheFilePath($targetClassFile), $srcLine, LOCK_EX)) {
            throw new Exception('cache write fail. [' . $this->getAopProxyCacheFilePath($targetClassFile) . ' ]');
        }
    }

    private function getAopProxyCacheFilePath($path) {
        return S2AOP_PHP5_FILE_CACHE_DIR
             . DIRECTORY_SEPARATOR
             . sha1($path)
             . '.aop';
    }
}
?>
