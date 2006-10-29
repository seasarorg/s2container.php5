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
// $Id:$
/**
 * @package org.seasar.extension.cache.factory
 * @author klove
 */
final class S2Container_CacheSupportFactory
{
    private static $support = null;
    const DEFAULT_CACHE_SUPPORT_CLASS = 'S2Container_PearCacheLiteSupport';
    private function __construct(){}

    /**
     * @retrun S2Container_CacheSupport singleton
     */
    public static function create() {
        if (self::$support === null) {
            $supportClassName = self::getSupportClassName();
            self::$support = new $supportClassName();
        }
        return self::$support;
    }

    private static function getSupportClassName() {
        return defined('S2CONTAINER_PHP5_CACHE_SUPPORT_CLASS') ?
               S2CONTAINER_PHP5_CACHE_SUPPORT_CLASS :
               self::DEFAULT_CACHE_SUPPORT_CLASS;
    }
}
?>
