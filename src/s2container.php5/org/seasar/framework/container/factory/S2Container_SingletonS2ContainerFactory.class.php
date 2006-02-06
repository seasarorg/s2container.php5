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
final class S2Container_SingletonS2ContainerFactory
{
    public static $INITIALIZE = true;
    private static $container_;
    
    /**
     * 
     */
    public static function getConfigPath()
    {
        return S2CONTAINER_PHP5_APP_DICON;
    }

    /**
     * 
     */
    public static function init($path = null)
    {
        if ($path == null) {
            if (!defined('S2CONTAINER_PHP5_APP_DICON')) {
                throw new S2Container_EmptyRuntimeException('S2CONTAINER_PHP5_APP_DICON');
            }
            self::$container_ = 
                         S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        } else {
            if (!is_readable($path)) {
                throw new S2Container_S2RuntimeException('ESSR0001',array($path));
            }            
            self::$container_ = S2ContainerFactory::create($path);
        }
        
        if(self::$INITIALIZE){
            self::$container_->init();
        }
    }
    
    /**
     * 
     */
    public static function destroy()
    {
        self::$container_->destroy();
        self::$container_ = null;
    }
    
    /**
     * 
     */
    public static function getContainer($path = null)
    {
        if (self::$container_ == null) {
            self::init($path);
        }
        return self::$container_;
    }
    
    /**
     * 
     */
    public static function setContainer($container)
    {
        self::$container_ = $container;
    }
    
    /**
     * 
     */
    public static function hasContainer()
    {
        return self::$container_ != null;
    }
}
?>
