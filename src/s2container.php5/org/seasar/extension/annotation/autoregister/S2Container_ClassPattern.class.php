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
 * @package org.seasar.extension.annotation.autoregister
 * @author klove
 */
class S2Container_ClassPattern {

    private $directoryPath;
    
    private $shortClassNamePatterns = array();
    
    public function __construct($directoryPath,$shortClassNames = null) {
        $this->setDirectoryPath($directoryPath);
        $this->setShortClassNames($shortClassNames);
    }
    
    public function getDirectoryPath() {
        return $this->directoryPath;
    }
    
    public function setDirectoryPath($directoryPath) {
        if(!is_dir($directoryPath)){
            throw new Exception("not dir [$directoryPath]");
        }
        $this->directoryPath= $directoryPath;
    }
    
    public function setShortClassNames($shortClassNames) {
        if(is_string($shortClassNames)){
            $this->shortClassNamePatterns = explode(',',$shortClassNames);
        }
    }
    
    public function isAppliedShortClassName($shortClassName) {
        if (count($this->shortClassNamePatterns) == 0) {
            return true;
        }
        
        foreach($this->shortClassNamePatterns as $pattern){
            if(preg_match("/$pattern/",$shortClassName)){
                return true;
            }
        }
        return false;
    }
}
?>
