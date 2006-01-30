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
abstract class S2Container_AbstractAutoRegister {

    const INIT_METHOD = "registerAll";

    protected $container;
    
    private $classPatterns = array();
    
    private $ignoreClassPatterns = array();
    
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
     * @param dir path string or S2Container_ClassPattern object
     * @param null or class name string
     */
    public function addClassPattern($arg1,$arg2 = null) {
        
        if($arg1 instanceof S2Container_ClassPattern){
            array_push($this->classPatterns,$arg1);
        }else{
            array_push($this->classPatterns,
                       new S2Container_ClassPattern($arg1,$arg2));
        }
    }

    /**
     * @param dir path string or S2Container_ClassPattern object
     * @param null or class name string
     */
    public function addIgnoreClassPattern($arg1,$arg2 = null) {
        
        if($arg1 instanceof S2Container_ClassPattern){
            array_push($this->ignoreClassPatterns,$arg1);
        }else{
            array_push($this->ignoreClassPatterns,
                       new S2Container_ClassPattern($arg1,$arg2));
        }
    }    

    public abstract function registerAll();

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
    
    protected function isIgnore($classFilePath,$shortClassName) {
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
