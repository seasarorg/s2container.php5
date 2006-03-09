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
 * @package org.seasar.framework.container
 * @author klove
 */
final class S2Container_TooManyRegistrationRuntimeException
    extends S2Container_S2RuntimeException
{
    private $key_;
    private $componentClasses_;

    /**
     * @param string key
     * @param array
     */
    public function __construct($key,$componentClasses)
    {
        /*
        $args = array($key);
        foreach ($componentClasses as $clazz) {
            array_push($args,$clazz->getName());
        }
        */
        $args[] = $key;
        $args[] = self::getClassNames($componentClasses);
        parent::__construct("ESSR0045",$args);
        $this->componentClasses_ = $componentClasses;
    }
    
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key_;
    }
    
    /**
     * @return array
     */
    public function getComponentClasses()
    {
        return $this->componentClasses_;
    }

    /**
     * @param array
     * @return array
     */
    private static function getClassNames($componentClasses)
    {
        $buf = array();

        foreach ($componentClasses as $clazz) {
            if($clazz instanceof ReflectionClass){
                array_push($buf,$clazz->getName());
            }else{
                array_push($buf,'class n/a');
            }
        }
        
        return implode(', ',$buf);
    }
}
?>
