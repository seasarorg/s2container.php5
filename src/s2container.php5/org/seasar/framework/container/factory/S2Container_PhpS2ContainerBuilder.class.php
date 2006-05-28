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
// $Id: S2Container_PhpS2ContainerBuilder.class.php 222 2006-03-07 09:58:42Z klove $
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class S2Container_PhpS2ContainerBuilder
    implements S2ContainerBuilder
{
    /**
     * 
     */
    public function includeChild(S2Container $parent, $path) 
    {
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }
         
    /**
     * 
     */
    public function build($path)
    {
        if (!is_readable($path)) {
            throw new S2Container_S2RuntimeException('ESSR0001',array($path));
        }
        $s2dicon = new S2Container_PhpS2ContainerBuilderHelper();
        require($path);
        
        if(isset($container) and $container instanceof S2Container){
            $container->setPath($path);
            return $container;
        }

        if(!$s2dicon instanceof S2Container_PhpS2ContainerBuilderHelper){
            throw new Exception("invalid PhpS2ContainerBuilderHelper found.");
        }
        $container = $s2dicon->getContainer();
        $container->setPath($path);
        return $s2dicon->getContainer();
    }
}
?>
