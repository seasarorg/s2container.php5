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
 * @package org.seasar.framework.container
 * @author klove
 */
class S2Container_CircularIncludeRuntimeException extends S2Container_S2RuntimeException {
    protected $path_;
    protected $paths_;

    /**
     * @param componentClasses
     */
    public function S2Container_CircularIncludeRuntimeException($path,$paths) {
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