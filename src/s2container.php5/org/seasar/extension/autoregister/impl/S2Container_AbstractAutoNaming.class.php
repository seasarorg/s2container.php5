<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
abstract class S2Container_AbstractAutoNaming 
    implements S2Container_AutoNaming
{

    const IMPL = "Impl";
    const BEAN = "Bean";

    protected $decapitalize = true;
    protected $customizedNames = array();
    protected $replaceRules = array();

    /**
     * 
     */
    public function __construct()
    {
        $this->addIgnoreClassSuffix(self::IMPL);
        $this->addIgnoreClassSuffix(self::BEAN);
    }

    /**
     * 
     */
    public function setCustomizedName($fqcn, $name)
    {
        $this->customizedNames[$fqcn] = $name;
    }

    /**
     * 
     */
    public function addIgnoreClassSuffix($classSuffix)
    {
        $this->addReplaceRule($classSuffix . '$', "");
    }

    /**
     * 
     */
    public function addReplaceRule($regex, $replacement)
    {
        $this->replaceRules[$regex] = $replacement;
    }

    /**
     * 
     */
    public function clearReplaceRule()
    {
        $this->customizedNames = array();
        $this->replaceRules = array();
    }

    /**
     * 
     */
    public function setDecapitalize($decapitalize)
    {
        $this->decapitalize = $decapitalize;
    }

    /**
     * 
     */
    public function defineName($directoryPath, $shortClassName)
    {
        $customizedName = $this->getCustomizedName($directoryPath,$shortClassName);
        if ($customizedName != null) {
            return $customizedName;
        }
        return $this->makeDefineName($directoryPath,$shortClassName);
    }

    /**
     * 
     */
    protected function getCustomizedName($directoryPath,$shortClassName) 
    {
        return isset($this->customizedNames[$shortClassName]) ? 
                     $this->customizedNames[$shortClassName] : null;
    }

    /**
     * 
     */
    abstract function makeDefineName($directoryPath,$shortClassName);

    /**
     * 
     */
    protected function applyRule($name)
    {
        foreach ($this->replaceRules as $key => $val) {
            $name = preg_replace("/$key/",$val,$name);
        }
        if ($this->decapitalize) {
            $name = strtolower($name[0]) . substr($name,1);
        }
        return $name;
    }
}
?>
