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
class S2Container_InterfaceAspectAutoRegister
{

    public static $INIT_METHOD = "registerAll";

    private $container;
    
    private $interceptor;
    
    private $targetInterface;
    
    private $pointcut;

    public function setContainer(S2Container $container) {
        $this->container = $container;
    }
    
    public function setInterceptor(S2Container_MethodInterceptor $interceptor) {
        $this->interceptor = $interceptor;
    }
    
    public function setTargetInterface($targetInterface) {
        if (!interface_exists($targetInterface)) {
            throw new S2Container_IllegalArgumentException($targetInterface);
        }
        $this->targetInterface = $targetInterface;
        $this->pointcut = new S2Container_PointcutImpl($targetInterface);
    }

    public function registerAll() {
        $c = $this->container->getComponentDefSize();
        for ($i = 0; $i < $c; ++$i) {
            $cd = $this->container->getComponentDef($i);
            $this->register($cd);
        }
    }
    
    protected function register(S2Container_ComponentDef $componentDef) {
        $componentClass = $componentDef->getComponentClass();
        if ($componentClass == null) {
            return;
        }
        if (! $componentClass->implementsInterface($this->targetInterface)) {
            return;
        }
        $this->registerInterceptor($componentDef);
    }
   
    protected function registerInterceptor(S2Container_ComponentDef $componentDef) {
        $aspectDef = new S2Container_AspectDefImpl($this->interceptor,
                                                   $this->pointcut);
        $componentDef->addAspectDef($aspectDef);
    }
}
?>
