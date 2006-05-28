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
 * @package org.seasar.extension.unit.simpletest
 * @author klove
 */
class S2Container_S2SimpleTestCase extends UnitTestCase
{
    private $container_;
    private $bindedFields_ = array();
    private $methodName_;
    
    /**
     * @return S2Container
     */
    public function getContainer()
    {
        return $this->container_;
    }

    /**
     * 
     */
    public function getName()
    {
        return $this->methodName_;
    }

    /**
     * @param string componentName
     * @return object
     * @see S2Container::getComponent()
     */
    public function getComponent($componentName)
    {
        return $this->container_->getComponent($componentName);
    }

    /**
     * @param string componentName
     * @return S2Container_ComponentDef
     * @see S2Container::getComponentDef()
     */
    public function getComponentDef($componentName)
    {
        return $this->container_->getComponentDef($componentName);
    }

    /**
     * @param object component 
     * @param string componentName
     * @see S2Container::register()
     */
    public function register($component,$componentName = "")
    {
        $this->container_->register($component, $componentName);
    }

    /**
     * Runs the bare test sequence.
     *
     * @access public
     */
    public function runBare($methodName)
    {
        $this->methodName_ = $methodName;
        $this->setUpContainer();
        $this->setUp();
        $this->setUpForEachTestMethod();
        $this->container_->init();
        $this->setUpAfterContainerInit();
        $this->bindFields();
        $this->setUpAfterBindFields();
        $this->$methodName();
        $this->tearDownBeforeUnbindFields();
        $this->unbindFields();
        $this->tearDownBeforeContainerDestroy();
        $this->tearDownContainer();
        $this->tearDownForEachTestMethod();
        $this->tearDown();
    }

    /**
     * Used to invoke the single tests.
     * @return SimpleInvoker Individual test runner.
     * @access public
     */
    function createInvoker()
    {
        return new SimpleErrorTrappingInvoker(new S2Container_S2SimpleInvoker($this));
    }
                        
    /**
     * @param string path
     */
    protected function includeDicon($path)
    {
        S2ContainerFactory::includeChild($this->container_,$path);
    }
    
    /**
     */
    protected function setUpContainer()
    {
        $this->container_ = new S2ContainerImpl();
        S2Container_SingletonS2ContainerFactory::setContainer($this->container_);        
    }

    /**
     * 
     */
    protected function setUpForEachTestMethod()
    {
        $targetName = $this->getTargetName();
        if ($targetName != "") {
            $this->invoke("setUp" . $targetName);
        }
    }    

    /**
     * 
     */
    protected function setUpAfterContainerInit()
    {
    }

    /**
     * 
     */
    protected function setUpAfterBindFields()
    {
    }

    /**
     */
    protected function tearDownBeforeUnbindFields()
    {
    }

    /**
     */
    protected function tearDownBeforeContainerDestroy()
    {
    }

    /**
     * 
     */
    protected function tearDownContainer()
    {
        $this->container_->destroy();
        S2Container_SingletonS2ContainerFactory::setContainer(null);
        $this->container_ = null;
    }

    /**
     */
    protected function tearDownForEachTestMethod()
    {
        $targetName = $this->getTargetName();
        if ($targetName != "") {
            $this->invoke("tearDown" . $targetName);
        }
        
    }
            
    private function unbindFields()
    {
        foreach ($this->bindedFields_ as $field) {
            $field->setValue($this, null);
        }
        $this->bindedFields_ = array();
    }
        
    private function bindFields()
    {
        $ref = new ReflectionClass($this);
        $props = $ref->getProperties();
        foreach ($props as $prop) {
            $this->bindField($prop);
        }
    }

    private function bindField(ReflectionProperty $field)
    {
        if ($this->isAutoBindable($field)) {
            $propName = $field->getName();
            if ($this->getContainer()->hasComponentDef($propName)) {
                $field->setValue($this,$this->getComponent($propName));
                $this->bindedFields_[] = $field;
            }
        }
    }

    private function isAutoBindable(ReflectionProperty $field)
    {
        return !$field->isStatic() and $field->isPublic() and
                !preg_match("/^SimpleTestCase/",$field->getDeclaringClass()->getName());
    }
        
    private function invoke($methodName)
    {
        try {
            $method = S2Container_ClassUtil::getMethod(new ReflectionClass($this),
                                           $methodName);
            S2Container_MethodUtil::invoke($method,$this, null);
        } catch (S2Container_NoSuchMethodRuntimeException $ignore) {
            //print "invoke ignored. [$methodName]\n";
        }
    }    

    private function getTargetName()
    {
        return substr($this->methodName_,4);
    }    
}
?>
