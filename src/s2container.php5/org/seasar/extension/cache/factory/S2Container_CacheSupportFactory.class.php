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
    public static $SUPPORT_CLASS_NAME = "S2Container_FileCacheSupport";
    private static $supports = array();

    private function __construct(){}

    public static function create() {
        $supportClassName = self::$SUPPORT_CLASS_NAME;
        if (!isset(self::$supports[$supportClassName])) {
            self::$supports[$supportClassName] = new $supportClassName();
        }
        return self::$supports[$supportClassName];
    }
}
?>
