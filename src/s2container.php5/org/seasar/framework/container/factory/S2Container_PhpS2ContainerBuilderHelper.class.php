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
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class S2Container_PhpS2ContainerBuilderHelper
{
    private $container;
    private $includeAvailable = true;
    
    public function __construct(){
        $this->init();
    }
    
    public function init(){
        $this->container = new S2ContainerImpl();
        $this->includeAvailable = true;     
    }

    public function getContainer(){
        S2Container_ChildComponentDefBindingUtil::bind($this->container);
        return $this->container;
    }

    public function includeChild($path){
        if(!$this->includeAvailable){
            throw new Exception("including child dicon must be done at first of all.");
        }
        S2ContainerFactory::includeChild($this->container,$path);
    }

    public function addComponent($classKey,
                                 $name = null,
                                 $instance = null,
                                 $autoBinding = null){
        if ($this->includeAvailable){
            S2Container_ChildComponentDefBindingUtil::init();   
            $this->includeAvailable = false;
        }

        $cd = new S2Container_ComponentDefImpl($classKey);
        if($name != null){
            $cd->setComponentName($name);
        }

        if($instance != null){
            $cd->setInstanceMode($instance);
        }

        if($autoBinding != null){
            $cd->setAutoBindingMode($autoBinding);
        }

        $this->container->register($cd);
        return $cd;
    }

    public function addArg(S2Container_ComponentDef $cd,$value){
        $def = new S2Container_ArgDefImpl();
        $def->setExpression($value);
        S2Container_ChildComponentDefBindingUtil::put($value,$def);
        $cd->addArgDef($def);
    }

    public function addProperty(S2Container_ComponentDef $cd,$name,$value){
        $def = new S2Container_PropertyDefImpl($name);
        $def->setExpression($value);
        S2Container_ChildComponentDefBindingUtil::put($value,$def);
        $cd->addPropertyDef($def);
    }

    public function addInitMethod(S2Container_ComponentDef $cd,$methods){
        $methods = explode(",",$methods);
        foreach($methods as $method){
            $def = new S2Container_InitMethodDefImpl($method);
            $cd->addInitMethodDef($def);
        }
    }

    public function addInitMethodArg(S2Container_ComponentDef $cd,
                                     $methodName,
                                     $value){
        $o = $cd->getInitMethodDefSize();
        for($i = 0; $i<$o ; $i++){
            $methodDef = $cd->getInitMethodDef($i);
            if($methodDef->getMethodName() == $methodName){
                $def = new S2Container_ArgDefImpl();
                $def->setExpression($value);
                S2Container_ChildComponentDefBindingUtil::put($value,$def);
                $methodDef->addArgDef($def);
                break;
            }
        }
    }

    public function addDestroyMethod(S2Container_ComponentDef $cd,$methods){
        $methods = explode(",",$methods);
        foreach($methods as $method){
            $def = new S2Container_DestroyMethodDefImpl($method);
            $cd->addDestroyMethodDef($def);
        }
    }

    public function addDestroyMethodArg(S2Container_ComponentDef $cd,
                                        $methodName,
                                        $value){
        $o = $cd->getDestroyMethodDefSize();
        for($i = 0; $i<$o ; $i++){
            $methodDef = $cd->getDestroyMethodDef($i);
            if($methodDef->getMethodName() == $methodName){
                $def = new S2Container_ArgDefImpl();
                $def->setExpression($value);
                S2Container_ChildComponentDefBindingUtil::put($value,$def);
                $methodDef->addArgDef($def);
                break;
            }
        }
    }

    public function addAspect(S2Container_ComponentDef $cd,$interceptor,$pointcut = null){

        if ($pointcut == null) {
            $pointcut = new S2Container_PointcutImpl($cd->getComponentClass());
        } else {
            $pointcuts = explode(",",$pointcut);
            $pointcut = new S2Container_PointcutImpl($pointcuts);
        }
        
        $def = new S2Container_AspectDefImpl($pointcut);
        $def->setExpression($interceptor);
        S2Container_ChildComponentDefBindingUtil::put($interceptor,$def);
        $cd->addAspectDef($def);
    }
}
?>
