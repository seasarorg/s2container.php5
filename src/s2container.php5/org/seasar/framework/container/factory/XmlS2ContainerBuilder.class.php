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
// $Id: XmlS2ContainerBuilder.class.php,v 1.3 2005/08/02 14:00:02 klove Exp $
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class XmlS2ContainerBuilder implements S2ContainerBuilder {

    public static $DTD_PATH21 = "components21.dtd";
    private $unresolvedCompRef_ = array();
    private $yetRegisteredCompRef_ = array();
 
    public function XmlS2ContainerBuilder (){
        $this->DTD_PATH21 = S2CONTAINER_PHP5 . "/org/seasar/framework/container/factory/components21.dtd";
    }

    public function build($path,$classLoader=null) {
        $container = null;
        
        if(!is_readable($path)){
            throw new S2RuntimeException('ESSR0001',array($path));
        }
        
        if(S2CONTAINER_PHP5_DOM_VALIDATE){
            $dom = new DomDocument();
            $dom->validateOnParse = true;
            $dom->load($path);
          
            if(!$dom->validate()){
                throw new S2RuntimeException('ESSR1001',array($path));
            }

            $root = simplexml_import_dom($dom);
        }else{
            $root = simplexml_load_file($path);
        }
        $namespace = trim((string)$root['namespace']);

        $container = new S2ContainerImpl();
        $container->setPath($path);
        if ($namespace != "") {
            $container->setNamespace($namespace); 
        }

        foreach($root->include as $index => $val){
            $path = trim((string)$val['path']);
            $path = StringUtil::expandPath($path);
            
            if(!is_readable($path)){
                throw new S2RuntimeException('ESSR0001',array($path));
            }
            $child = S2ContainerFactory::includeChild($container,$path);
            $child->setRoot($container->getRoot());
               
        }
           
        foreach($root->component as $index => $val){
            $container->register($this->setupComponentDef($val));               
        }
           
        $this->setupMetaDef($root,$container);
           
        foreach($this->yetRegisteredCompRef_ as $compRef){
           	$container->register($compRef);
        }
        $this->yetRegisteredCompRef_ = array();
           
        if(count(array_keys($this->unresolvedCompRef_)>0)){
            foreach ($this->unresolvedCompRef_ as $key => $val){
                foreach($val as $argDef){
                    if($container->hasComponentDef($key)){
                        $argDef->setChildComponentDef($container->getComponentDef($key));
                        $argDef->setExpression("");
                    }
                }
            }
        }
        $this->unresolvedCompRef_ = array();
        return $container;
    }

    private function setupComponentDef($component){
        $className = trim((string)$component['class']);
        $name = trim((string)$component['name']);
        $instanceMode = trim((string)$component['instance']);
        $autoBindingMode = trim((string)$component['autoBinding']);
        
        $componentDef = new ComponentDefImpl($className,$name);

        $compExp = trim((string)$component);
        if($compExp != ""){
            $componentDef->setExpression($compExp);        	
        }   

        if ($instanceMode != "") {
            $componentDef->setInstanceMode($instanceMode);
        }

        if ($autoBindingMode != "") {
            $componentDef->setAutoBindingMode($autoBindingMode);
        }
           
        foreach($component->arg as $index => $val){
            $componentDef->addArgDef($this->setupArgDef($val));               
        }

        foreach($component->property as $index => $val){
            $componentDef->addPropertyDef($this->setupPropertyDef($val));               
        }

        foreach($component->initMethod as $index => $val){
            $componentDef->addInitMethodDef($this->setupInitMethodDef($val));               
        }

        foreach($component->destroyMethod as $index => $val){
            $componentDef->addDestroyMethodDef($this->setupDestroyMethodDef($val));               
        }

        foreach($component->aspect as $index => $val){
            $componentDef->addAspectDef($this->setupAspectDef($val,$className));               
        }
           
        $this->setupMetaDef($component,$componentDef);

        return $componentDef;
    }    
    
    private function setupArgDef($arg){
        $argDef = new ArgDefImpl();

        if(count($arg->component[0]) == null){
            $argValue = trim((string)$arg);
            if(preg_match("/^\"(.+)\"$/",$argValue,$regs) or
               preg_match("/^\'(.+)\'$/",$argValue,$regs)){
                $argDef->setValue($regs[1]);
            }else{
                 $argDef->setExpression($argValue);
                if(array_key_exists($argValue,$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[$argValue],$argDef);
                }else{
                   $this->unresolvedCompRef_[$argValue] = array($argDef);
                }
            }
        }else{
            $childComponent = $this->setupComponentDef($arg->component[0]);
            $argDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_,$childComponent);
        }

        $this->setupMetaDef($arg,$argDef);
        
        return $argDef;
    }

    private function setupPropertyDef($property){
        $name = (string)$property['name'];
        $propertyDef = new PropertyDefImpl($name);

        if(count($property->component[0]) == null){
            $propertyValue = trim((string)$property);
            if(preg_match("/^\"(.+)\"$/",$propertyValue,$regs) or
               preg_match("/^\'(.+)\'$/",$propertyValue,$regs)){
                  $propertyDef->setValue($regs[1]);
            }else{
                 $propertyDef->setExpression($propertyValue);
                if(array_key_exists($propertyValue,$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[$propertyValue],$propertyDef);
                }else{
                   $this->unresolvedCompRef_[$propertyValue] = array($propertyDef);
                }
            }
        }else{
            $childComponent = $this->setupComponentDef($property->component[0]);
            $propertyDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_,$childComponent);        	
        }

        $this->setupMetaDef($property,$propertyDef);
        
        return $propertyDef;
    }
    
    private function setupInitMethodDef($initMethod){
        $name = (string)$initMethod['name'];
        $exp = trim((string)$initMethod);

        $initMethodDef = new InitMethodDefImpl($name);
        if($exp != ""){
        	$initMethodDef->setExpression($exp);
        }
        foreach($initMethod->arg as $index => $val){
            $initMethodDef->addArgDef($this->setupArgDef($val));               
        }

        return $initMethodDef;
    }

    private function setupDestroyMethodDef($destroyMethod){
        $name = (string)$destroyMethod['name'];
        $exp = trim((string)$destroyMethod);
        $destroyMethodDef = new DestroyMethodDefImpl($name);
        if($exp != ""){
        	$destroyMethodDef->setExpression($exp);
        }

        foreach($destroyMethod->arg as $index => $val){
            $destroyMethodDef->addArgDef($this->setupArgDef($val));               
        }

        return $destroyMethodDef;
    }

    private function setupAspectDef($aspect,$targetClassName){
        $pointcut = trim((string)$aspect['pointcut']);

        if($pointcut == ""){
            $pointcut = new PointcutImpl($targetClassName);
        }else{
            $pointcuts = split(",",$pointcut);
            $pointcut = new PointcutImpl($pointcuts);
        }
        
        $aspectDef = new AspectDefImpl($pointcut);
        if(count($aspect->component[0]) == null){
            $aspectValue = trim((string)$aspect);
            if(preg_match("/^\"(.+)\"$/",$aspectValue,$regs) or
               preg_match("/^\'(.+)\'$/",$aspectValue,$regs)){
                  $aspectDef->setValue($regs[1]);
            }else{
                 $aspectDef->setExpression($aspectValue);
                if(array_key_exists($aspectValue,$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[$aspectValue],$aspectDef);
                }else{
                   $this->unresolvedCompRef_[$aspectValue] = array($aspectDef);
                }
            }
        }else{
            $childComponent = $this->setupComponentDef($aspect->component[0]);
            $aspectDef->setChildComponentDef($childComponent);
            array_push($this->yetRegisteredCompRef_,$childComponent);        	
        }
        
        return $aspectDef;
    }
    
    private function setupMetaDef($parent,$parentDef){
        foreach($parent->meta as $index => $val){
            $name = trim((string)$val['name']);
            $metaDef = new MetaDefImpl($name);

            if(count($val->component[0]) == null){
                $metaValue = trim((string)$val);
                if(preg_match("/^\"(.+)\"$/",$metaValue,$regs) or
                   preg_match("/^\'(.+)\'$/",$metaValue,$regs)){
                   $metaDef->setValue($regs[1]);
                }else{
                     $metaDef->setExpression($metaValue);
                    if(array_key_exists($metaValue,$this->unresolvedCompRef_)){
                       array_push($this->unresolvedCompRef_[$metaValue],$metaDef);
                    }else{
                       $this->unresolvedCompRef_[$metaValue] = array($metaDef);
                    }
                }
            }else{
                $childComponent = $this->setupComponentDef($meta->component[0]);
                $metaDef->setChildComponentDef($childComponent);
                array_push($this->yetRegisteredCompRef_,$childComponent);        	
            }
            $parentDef->addMetaDef($metaDef);
        }
    }
    
    public function includeChild(S2Container $parent, $path) {
        $child = null;

        $child = $this->build($path);
        $parent->includeChild($child);

        return $child;
    }
}
?>