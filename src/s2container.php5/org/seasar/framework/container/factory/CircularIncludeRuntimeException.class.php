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
class CircularIncludeRuntimeException extends S2RuntimeException {
    protected $path_;
    protected $paths_;

    /**
     * @param componentClasses
     */
    public function CircularIncludeRuntimeException($path,$paths) {
        parent::__construct("ESSR0076",array($path,$this->toString($path,$paths)));
        $this->path_ = $path;
        $this->paths_ = $paths;
    }

    public function getPath() {
        return $this->path_;
    }

    public function getPaths() {
        return $this->paths_;
    }

    protected static function toString($path, $paths) {
        $buf = "";
        foreach($paths as $val){
            $buf .= '"' . $val . '"';
            $buf .= " includes ";
        }
        $buf .= '"' . $path . '"';
        return $buf;
    }
}
?>
