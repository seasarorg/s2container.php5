<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class SingletonS2ContainerFactory {

    private static $container_;
    
    private function SingletonS2ContainerFactory() {
    }
    
    public static function getConfigPath() {
        return S2CONTAINER_PHP5_APP_DICON;
    }

    public static function init($path=null) {
        if($path==null){
            if(!defined('S2CONTAINER_PHP5_APP_DICON')){
                throw new EmptyRuntimeException('S2CONTAINER_PHP5_APP_DICON');
            }
            SingletonS2ContainerFactory::$container_ = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        }else{
            if(!is_readable($path)){
                throw new S2RuntimeException('ESSR0001',array($path));
            }            
            SingletonS2ContainerFactory::$container_ = S2ContainerFactory::create($path);
        }
        SingletonS2ContainerFactory::$container_->init();
    }
    
    public static function destroy() {
        SingletonS2ContainerFactory::$container_->destroy();
        SingletonS2ContainerFactory::$container_ = null;
    }
    
    public static function getContainer($path=null) {
        if (SingletonS2ContainerFactory::$container_ == null) {
            SingletonS2ContainerFactory::init($path);
        }
        return SingletonS2ContainerFactory::$container_;
    }
    
    public static function setContainer($container) {
        SingletonS2ContainerFactory::$container_ = $container;
    }
    
    public static function hasContainer() {
        return SingletonS2ContainerFactory::$container_ != null;
    }
}
?>