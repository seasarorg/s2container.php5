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
 * @package org.seasar.framework.container
 * @author klove
 */
final class TooManyRegistrationRuntimeException extends S2RuntimeException {

    private $key_;
    private $componentClasses_;

    public function TooManyRegistrationRuntimeException(
        $key,$componentClasses) {

        $args = array($key);
        foreach($componentClasses as $clazz){
            array_push($args,$clazz->getName());
        }
        parent::__construct("ESSR0045",$args);
        $this->componentClasses_ = $componentClasses;
    }
    
    public function getKey() {
        return $this->key_;
    }
    
    public function getComponentClasses() {
        return $this->componentClasses_;
    }

    private static function getClassNames($componentClasses) {
        $buf = "";
        for ($i = 0; $i < count($componentClasses); ++$i) {
            $buf .= $componentClasses[$i];
            $buf .= ", ";
        }
        return $buf;
    }
}
?>
