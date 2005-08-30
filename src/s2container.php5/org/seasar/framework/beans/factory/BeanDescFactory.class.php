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
 * BeanDesc‚ðì¬‚µ‚Ü‚·B
 * 
 * @package org.seasar.framework.beans.factory
 * @author klove
 */
final class BeanDescFactory {

    private static $beanDescCache_ = array();

    /**
     * Singleton‚Ì‚½‚ßprivate
     */
    private function BeanDescFactory() {
    }

    public static function getBeanDesc(ReflectionClass $clazz) {
    	
    	if(array_key_exists($clazz->getName(),BeanDescFactory::$beanDescCache_)){
            $beanDesc = BeanDescFactory::$beanDescCache_[$clazz->getName()];
    	}else{
            $beanDesc = new BeanDescImpl($clazz);
            BeanDescFactory::$beanDescCache_[$clazz->getName()] = $beanDesc;
        }
        return $beanDesc;
    }
}
?>
