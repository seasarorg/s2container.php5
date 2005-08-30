<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
abstract class AbstractAssembler {
    
    private $log_;
    
    private $componentDef_;

    public function AbstractAssembler(ComponentDef $componentDef) {
        $this->componentDef_ = $componentDef;
        $this->log_ = S2Logger::getLogger(get_class($this));
    }

    protected final function getComponentDef() {
        return $this->componentDef_;
    }

    /**
     * @param object
     */
    protected final function getBeanDesc($component=null) {
        if(!is_object($component)){
            return BeanDescFactory::getBeanDesc(
                $this->getComponentDef()->getComponentClass());
        }
        
        return BeanDescFactory::getBeanDesc(
            $this->getComponentClass($component));
    }
    
    /**
     * @param object
     */
    protected final function getComponentClass($component) {
        $clazz = $this->componentDef_->getComponentClass();
        if ($clazz != null) {
            return $clazz;
        } else {
            return new ReflectionClass($component);
        }
    }

    /**
     * @param ReflectionParameter[] 
     */    
    protected function getArgs($argTypes) {
        $args = array();
        for ($i = 0; $i < count($argTypes); ++$i) {
            try {
                if($argTypes[$i]->getClass() != null &&
                    AutoBindingUtil::isSuitable($argTypes[$i]->getClass())){
                    $args[$i]= $this->getComponentDef()->getContainer()->getComponent($argTypes[$i]->getClass()->getName());
                }else{
                	if($argTypes[$i]->isOptional()){
                        $args[$i] = $argTypes[$i]->getDefaultValue();
                	}else{
                		$args[$i] = null;
                	}
                }
            } catch (ComponentNotFoundRuntimeException $ex) {
                $this->log_->warn($ex->getMessage(),__METHOD__);
                $args[$i] = null;
            }
        }
        return $args;
    }
}
?>