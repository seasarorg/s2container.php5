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
    public static $BUILDER_CONFIG_PATH = null;

    private static $instance = null;

    private $builderProps_;
    private $builders_ = array();
    private $defaultBuilder_;
    private $processingPaths_ = array();
        
    /**
     * 
     */
    private function __construct()
    {
        $this->defaultBuilder_ = 
                    new S2Container_XmlS2ContainerBuilder();
        $this->builders_['xml'] = $this->defaultBuilder_;
        $this->builders_['dicon'] = $this->defaultBuilder_;

        $this->_setupBuilderProps();
    }


    /**
     * 
     */
    public static function getInstance()
    {
        if(self::$instance == null ){
            self::$instance = new S2ContainerFactory();
        }
        return self::$instance;   
    }

    /**
     * @param string dicon path 
     */
    public static function create($path) 
    {
        return self::getInstance()->_createInternal($path);
    }
    
    /**
     * 
     */
    public static function includeChild(S2Container $parent, $path)
    {
        return self::getInstance()->_includeChildInternal($parent, $path);
    }
    
    /**
     * 
     */
    private function _createInternal($path) 
    {
        $this->_enter($path);
        $ext = $this->_getExtension($path);
        $container = $this->_getBuilder($ext)->build($path);
        $this->_leave($path);

        return $container;
    }
    
    /**
     * 
     */
    private function _includeChildInternal(S2Container $parent, $path)
    {
        $this->_enter($path);
        $root = $parent->getRoot();
        $child = null;
        if ($root->hasDescendant($path)) {
            $child = $root->getDescendant($path);
            $parent->includeChild($child);
        } else {
            $ext = $this->_getExtension($path);
            $builder = $this->_getBuilder($ext);
            $child = $builder->includeChild($parent,$path);
            $root->registerDescendant($child);
        }
        $this->_leave($path);
        return $child;
    }

    /**
     * 
     */
    private function _setupBuilderProps(){

        if(self::$BUILDER_CONFIG_PATH == null){
            self::$BUILDER_CONFIG_PATH = 
                S2CONTAINER_PHP5 . "/S2CntainerBuilder.properties";
        }
        
        if (is_readable(self::$BUILDER_CONFIG_PATH)) {
               $this->builderProps_ = 
                   parse_ini_file(self::$BUILDER_CONFIG_PATH);
        }
    }
    
    /**
     * 
     */
    private function _getExtension($path)
    {
        $filename = basename($path);
        preg_match('/\.([a-zA-Z0-9]+?)$/',$filename,$regs);
        return $regs[1];
    }
    
    /**
     * 
     */
    private function _getBuilder($ext)
    {
        $builder = null;

        if (array_key_exists($ext,$this->builders_)) {
            $builder = $this->builders_[$ext];
            if ($builder != null) {
                return $builder;
            }
        }
        
        $className = $this->builderProps_[$ext];
        if ($className != null) {
            $builder = new $className();
            $this->builders_[$ext] = $builder;
        } else {
            $builder = $this->defaultBuilder_;
        }
        return $builder;
    }

    /**
     * 
     */
    private function _enter($path)
    {
        if (in_array($path,$this->processingPaths_)) {
            throw new S2Container_CircularIncludeRuntimeException($path,
                                  $this->processingPaths_);
        }
        $this->processingPaths_[] = $path;
    }

    /**
     * 
     */
    private function _leave($path)
    {
        array_pop($this->processingPaths_);
    }
}
?>
