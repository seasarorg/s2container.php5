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
 * @package org.seasar.extension.annotation.autoregister
 * @author klove
 */
class S2Container_AspectAutoRegister 
    extends S2Container_AbstractAutoRegister
{
    private $interceptor;
    private $pointcut;

    public function setInterceptor(S2Container_MethodInterceptor $interceptor) {
        $this->interceptor = $interceptor;
    }

    public function setPointcut($pointcut) {
        $this->pointcut = $pointcut;
    }

    /**
     * @param string class name string
     */
    public function addClassPattern($patterns) {
        $pat = new S2Container_ClassPattern();
        $pat->setShortClassNames($patterns);
        parent::addClassPatternInternal($pat);
    }

    /**
     * @param string class name string
     */
    public function addIgnoreClassPattern($patterns) {
        $pat = new S2Container_ClassPattern();
        $pat->setShortClassNames($patterns);        
        parent::addIgnoreClassPatternInternal($pat);
    }

    public function registerAll() {
        $container = $this->getContainer();
        $c = $container->getComponentDefSize();
        for ($i = 0; $i < $c; ++$i) {
            $cd = $container->getComponentDef($i);
            $this->register($cd);
        }
    }
    
    protected function register(S2Container_ComponentDef $componentDef) {
        $componentClass = $componentDef->getComponentClass();
        if ($componentClass == null) {
            return;
        }

        $shortClassName = $componentClass->getName();
        $c = $this->getClassPatternSize();
        for ($i = 0; $i < $c; ++$i) {
            $cp = $this->getClassPattern($i);
            if ($this->isIgnore($shortClassName)) {
                continue;
            }
            if ($cp->isAppliedShortClassName($shortClassName)) {
                $this->registerInterceptor($componentDef);
                return;
            }
        }
    }

    protected function registerInterceptor(S2Container_ComponentDef $componentDef) {
        if(is_string($this->pointcut)){
            $pointcut = new S2Container_PointcutImpl(
                            explode(',',$this->pointcut));
        }else{
            $pointcut = new S2Container_PointcutImpl(
                            $componentDef->getComponentClass());
        }
        
        $aspectDef = new S2Container_AspectDefImpl($this->interceptor,
                                                   $pointcut);
        $componentDef->addAspectDef($aspectDef);
    }
}
?>
