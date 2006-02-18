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
 * @package org.seasar.extension.autoregister.autoregister
 * @author klove
 */
class S2Container_FileSystemComponentAutoRegister 
    extends S2Container_AbstractComponentAutoRegister
{
    /**
     * 
     */
    public function registerAll() {
        S2Container_ChildComponentDefBindingUtil::init();

        $c = $this->getClassPatternSize();
        for ($i = 0; $i < $c; ++$i) {
            $cp = $this->getClassPattern($i);
            $this->registerInternal($cp);
        }
        S2Container_ChildComponentDefBindingUtil::bind($this->getContainer());
        
    }

    /**
     * @param string dir path
     * @param string null or class name string
     */
    public function addClassPattern($directoryPath,$patterns = null) {

        $directoryPath = S2Container_StringUtil::expandPath($directoryPath);
        
        if(!is_dir($directoryPath)){
            throw new Exception("not dir [$directoryPath]");
        }

        $pat = new S2Container_ClassPattern();
        $pat->setDirectoryPath($directoryPath);
        $pat->setShortClassNames($patterns);
        
        parent::addClassPatternInternal($pat);
    }

    /**
     * @param string null or class name string
     */
    public function addIgnoreClassPattern($patterns) {
        
        $pat = new S2Container_ClassPattern();
        $pat->setShortClassNames($patterns);
        
        parent::addIgnoreClassPatternInternal($pat);
    }
    
    /**
     * 
     */
    protected function registerInternal(S2Container_ClassPattern $classPattern) {
        S2Container_ClassTraversal::forEachTime(
            $classPattern->getDirectoryPath(), $this);
    }
}
?>
