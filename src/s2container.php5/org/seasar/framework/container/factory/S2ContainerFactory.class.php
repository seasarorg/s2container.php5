<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
final class S2ContainerFactory {

    public static $DTD_PATH;
    public static $BUILDER_CONFIG_PATH;
    private static $builderProps_;
    private static $builders_ = array();
    private static $defaultBuilder_;
    private static $inited_ = false;
    protected static $processingPaths_ = array();
    
    private function S2ContainerFactory() {
        $this->init();
    }

    private static function init(){
        if(!S2ContainerFactory::$inited_){
            S2ContainerFactory::$defaultBuilder_ = new S2Container_XmlS2ContainerBuilder();
            S2ContainerFactory::$DTD_PATH = S2CONTAINER_PHP5 . "/org/seasar/framework/container/factory/components21.dtd";
            S2ContainerFactory::$BUILDER_CONFIG_PATH = S2CONTAINER_PHP5 . "/S2CntainerBuilder.properties";

            if(is_readable(S2ContainerFactory::$BUILDER_CONFIG_PATH)){
                   S2ContainerFactory::$builderProps_ = parse_ini_file(S2ContainerFactory::$BUILDER_CONFIG_PATH);
            }
            S2ContainerFactory::$builders_['xml'] = S2ContainerFactory::$defaultBuilder_;
            S2ContainerFactory::$builders_['dicon'] = S2ContainerFactory::$defaultBuilder_;
            S2ContainerFactory::$inited_ = true;
        }
    }

    public static function create($path) {

        if(S2Container_FileCacheUtil::isContainerCache()){
            $container = S2Container_FileCacheUtil::getCachedContainer($path);
            if(is_object($container)){
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;
            }
        }

        S2ContainerFactory::init();
        S2ContainerFactory::enter($path);
        $ext = S2ContainerFactory::getExtension($path);
        $container = S2ContainerFactory::getBuilder($ext)->build($path);
        S2ContainerFactory::leave($path);

        if(S2Container_FileCacheUtil::isContainerCache()){
            S2Container_FileCacheUtil::writeContainerCache($path,$container);
        }

        return $container;
    }
    
    public static function includeChild(S2Container $parent, $path) {
        S2ContainerFactory::init();
        S2ContainerFactory::enter($path);
        $root = $parent->getRoot();
        $child = null;
        if ($root->hasDescendant($path)) {
            $child = $root->getDescendant($path);
            $parent->includeChild($child);
        } else {
            $ext = S2ContainerFactory::getExtension($path);
            $builder = S2ContainerFactory::getBuilder($ext);
            $child = $builder->includeChild($parent,$path);
            $root->registerDescendant($child);
        }
        S2ContainerFactory::leave($path);
        return $child;
    }
    
    private static function getExtension($path) {
        $filename = basename($path);
        preg_match('/\.([a-zA-Z0-9]+)$/',$filename,$regs);
        return $regs[1];
    }
    
    private static function getBuilder($ext) {
        $builder = null;

        if(array_key_exists($ext,S2ContainerFactory::$builders_)){
            $builder = S2ContainerFactory::$builders_[$ext];
            if ($builder != null) {
                return $builder;
            }
        }
        
        $className = S2ContainerFactory::$builderProps_[$ext];
        if ($className != null) {
            $builder = new $className();
            S2ContainerFactory::$builders_[$ext] = $builder;
        } else {
            $builder = S2ContainerFactory::$defaultBuilder_;
        }
        return $builder;
    }

    protected static function enter($path) {
        if (in_array($path,S2ContainerFactory::$processingPaths_)){
            throw new S2Container_CircularIncludeRuntimeException(
                          $path, S2ContainerFactory::$processingPaths_);
        }
        array_push(S2ContainerFactory::$processingPaths_,$path);
    }

    protected static function leave($path) {
    	array_pop(S2ContainerFactory::$processingPaths_);
    }
}
?>