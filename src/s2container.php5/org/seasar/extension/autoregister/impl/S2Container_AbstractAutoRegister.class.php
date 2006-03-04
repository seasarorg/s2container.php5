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
 * @package org.seasar.extension.autoregister.impl
 * @author klove
 */
abstract class S2Container_AbstractAutoRegister {

    protected $container;
    private $classPatterns = array();
    private $ignoreClassPatterns = array();
    
    public abstract function registerAll();

    public function getContainer() {
        return $this->container;
    }

    public function setContainer(S2Container $container) {
        $this->container = $container;    
    }

    public function getClassPatternSize() {
        return count($this->classPatterns);
    }
    
    public function getClassPattern($index) {
        return isset($this->classPatterns[$index]) ? 
                     $this->classPatterns[$index] : null;
    }
    
    /**
     * @param S2Container_ClassPattern object
     */
    protected function addClassPatternInternal(S2Container_ClassPattern $pattern) {
        array_push($this->classPatterns,$pattern);
    }

    /**
     * @param S2Container_ClassPattern object
     */
    protected function addIgnoreClassPatternInternal(S2Container_ClassPattern $pattern) {
        array_push($this->ignoreClassPatterns,$pattern);
    }    

    protected function hasComponentDef($name) {
        return $this->findComponentDef($name) != null;
    }

    protected function findComponentDef($name) {
        if (!is_string($name)) {
            return null;
        }
        
        $container = $this->getContainer();
        $c = $container->getComponentDefSize();
        for ($i = 0; $i < $c; ++$i) {
            $cd = $container->getComponentDef($i);
            if ($name == $cd->getComponentName()) {
                return $cd;
            }
        }
        return null;
    }
    
    protected function isIgnore($shortClassName) {
        if (count($this->ignoreClassPatterns) == 0) {
            return false;
        }
        $c = count($this->ignoreClassPatterns);
        for ($i = 0; $i < $c; ++$i) {
            $cp = $this->ignoreClassPatterns[$i];
            if ($cp->isAppliedShortClassName($shortClassName)) {
                return true;
            }
        }
        return false;
    }
}
?>
