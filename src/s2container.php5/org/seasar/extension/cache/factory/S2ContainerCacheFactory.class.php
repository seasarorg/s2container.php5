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
 * @package org.seasar.extension.cache.factory
 * @author klove
 * @deprecated deprecated since version 1.1.2
 */
final class S2ContainerCacheFactory
{
    public static $INITIALIZE_BEFORE_CACHE = false;

    /**
     * @param string dicon path 
     */
    public static function create($diconPath) 
    {
        $support = S2Container_CacheSupportFactory::create();

        if (!$support->isContainerCaching($diconPath)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("set caching off.",__METHOD__);
            return S2ContainerFactory::create($diconPath);
        }

        if ($data = $support->loadContainerCache($diconPath)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                    info("cached container found.",__METHOD__);
            $container = unserialize($data);
            if (is_object($container) and 
                $container instanceof S2Container) {
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;
            } else {
                 throw new Exception("invalid cache found.");
            }
        }
        else {
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("create container and cache it.",__METHOD__);
            $container = S2ContainerFactory::create($diconPath);

            if (self::$INITIALIZE_BEFORE_CACHE) {
                $container->init(); 
            }
            $support->saveContainerCache(serialize($container), $diconPath);
            return $container;
        }
    }
}
?>
