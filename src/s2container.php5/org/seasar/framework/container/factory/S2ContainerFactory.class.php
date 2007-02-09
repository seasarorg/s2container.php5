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
// $Id$
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class S2ContainerFactory
{
    public static $BUILDERS = array();

    private static $instance = null;
    private static $builders_ = array();
    private static $processingPaths_ = array();

    private function __construct()
    {
    }

    private static function getEnvDicon($path) {
        $pinfo = pathinfo($path);
        $pattern = '/\./';
        $replacement = '_' . S2CONTAINER_PHP5_ENV . '.';
        $envDicon = $pinfo['dirname']
                  . DIRECTORY_SEPARATOR
                  . preg_replace($pattern, $replacement, $pinfo['basename'], 1);
        if (is_readable($envDicon)) {
            return $envDicon;
        }
        
        return null;
    }

    /**
     * @param string dicon path 
     */
    public static function create($diconPath) 
    {
        $support = S2Container_CacheSupportFactory::create();

        if (!$support->isContainerCaching($diconPath)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("set caching off.",__METHOD__);
            return self::createInternal($diconPath);
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
            $container = self::createInternal($diconPath);
            $support->saveContainerCache(serialize($container), $diconPath);
            return $container;
        }
    }
    
    
    /**
     * @param string dicon path 
     */
    private static function createInternal($path) 
    {
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001',array($path));
        }

        if (defined('S2CONTAINER_PHP5_ENV')) {
            $envDicon = self::getEnvDicon($path);
            $path = $envDicon === null ? $path : $envDicon;
        }

        self::_enter($path);
        $ext = pathinfo($path,PATHINFO_EXTENSION);
        $container = null;
        try {
            $container = self::_getBuilder($ext)->build($path);
            self::_leave($path);
        } catch (Exception $e) {
            self::_leave($path);
            throw $e;
        }

        return $container;
    }
    
    /**
     * 
     */
    public static function includeChild(S2Container $parent, $path)
    {
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001',array($path));
        }
        if (defined('S2CONTAINER_PHP5_ENV')) {
             $envDicon = self::getEnvDicon($path);
             $path = $envDicon === null ? $path : $envDicon;
        }

        self::_enter($path);
        $root = $parent->getRoot();
        $child = null;
        try {
            if ($root->hasDescendant($path)) {
                $child = $root->getDescendant($path);
                $parent->includeChild($child);
            } else {
                $ext = pathinfo($path,PATHINFO_EXTENSION);
                $builder = self::_getBuilder($ext);
                $child = $builder->includeChild($parent,$path);
                $root->registerDescendant($child);
            }
            self::_leave($path);

        } catch (Exception $e) {
            self::_leave($path);
            throw $e;
        }

        return $child;
    }

    /**
     * 
     */
    private static function _getBuilder($ext)
    {
        $builder = null;

        if (array_key_exists($ext,self::$builders_)) {
            return self::$builders_[$ext];
        }

        $className = array_key_exists($ext,self::$BUILDERS) 
                     ? self::$BUILDERS[$ext]
                     : null;
        if ($className != null) {
            $builder = new $className();
            if (!$builder instanceof S2ContainerBuilder) {
                throw new S2Container_S2RuntimeException('ESSR1011',
                    array($ext,$className),
                    null);
            }
            self::$builders_[$ext] = $builder;
        } else {
            throw new S2Container_S2RuntimeException('ESSR1012',
                array($ext),
                null);
        }
        return $builder;
    }

    /**
     * 
     */
    private static function _enter($path)
    {
        if (in_array($path,self::$processingPaths_)) {
            throw new S2Container_CircularIncludeRuntimeException($path,
                                  self::$processingPaths_);
        }
        array_push(self::$processingPaths_,$path);
    }

    /**
     * 
     */
    private static function _leave($path)
    {
        array_pop(self::$processingPaths_);
    }
}
?>
