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
// $Id: IniS2ContainerBuilder.class.php,v 1.2 2005/06/21 16:33:48 klove Exp $
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
final class IniS2ContainerBuilder implements S2ContainerBuilder {
    private $unresolvedCompRef_ = array();

    public function IniS2ContainerBuilder (){}

    public function build($path,$classLoader=null) {
        $container = null;
        
        if(!is_readable($path)){
            throw new S2RuntimeException('ESSR0001',array($path));
        }
        
        $root = parse_ini_file($path,true);
        $container = new S2ContainerImpl();
        $container->setPath($path);
        if(array_key_exists('components',$root)){
        	$components = $root['components'];
            array_key_exists('namespace',$components) ? $namespace = trim($components['namespace']) : $namespace ="";
            if($namespace != ""){
            	$container->setNamespace($namespace);
            } 

            for($i=0;;$i++){
                if(array_key_exists('include{'.$i.'}',$components)){
                    $path = trim($components['include{'.$i.'}']);
                    $path = StringUtil::expandPath($path);
                    if(!is_readable($path)){
                        throw new S2RuntimeException('ESSR0001',array($path));
                    }
                    $child = S2ContainerFactory::includeChild($container,$path);
                    $child->setRoot($container->getRoot());
                }else{
                    break;	
                }
               
            }

            $this->setupMetaDef($container,$components);
        }

        foreach($root as $key => $val){
        	if($key == 'components'){
        		continue;
        	}
        	
            $container->register($this->setupComponentDef($key,$val));               
        }
           
        if(count(array_keys($this->unresolvedCompRef_)>0)){
            foreach ($this->unresolvedCompRef_ as $key => $val){
                foreach($val as $argDef){
                    if($container->hasComponentDef($key)){
                        $argDef->setChildComponentDef($container->getComponentDef($key));
                    }
                }
            }
        }
        $this->unresolvedCompRef_ = array();

        return $container;
    }

    private function setupComponentDef($name,&$component){
        if(array_key_exists('class',$component)){
            $className = trim($component['class']);
        }else{
            if(array_key_exists('exp',$component)){
                $className = "";
            }else{
                $className = $name;
                $name = "";
            }        	
        }   
        
        $componentDef = new ComponentDefImpl($className,$name);

        if(array_key_exists('exp',$component)){
            $componentDef->setExpression(trim($component['exp']));        	
        }   
        
        if (array_key_exists('instance',$component)) {
            $componentDef->setInstanceMode(trim($component['instance']));
        }

        if (array_key_exists('autobinding',$component)) {
            $componentDef->setAutoBindingMode(trim($component['autobinding']));
        }

        $this->setupArgDef($componentDef,$component);
        $this->setupPropertyDef($componentDef,$component);
        $this->setupInitMethodDef($componentDef,$component);
        $this->setupDestroyMethodDef($componentDef,$component);
        $this->setupAspectDef($componentDef,$component,$className);
        
        $this->setupMetaDef($componentDef,$component);

        return $componentDef;
    }    
    
    private function setupArgDef($componentDef,&$component,$parent=""){

        for($i=0;;$i++){
            $argDef = null;
            
            if($parent == ""){
           	    $key_val = 'arg{'.$i.'}{val}';
           	    $key_ref = 'arg{'.$i.'}{ref}';
           	    $key_exp = 'arg{'.$i.'}{exp}';
           	    $key_arg = 'arg{'.$i.'}';
            }else{     
           	    $key_val = $parent.'{arg{'.$i.'}{val}}';
           	    $key_ref = $parent.'{arg{'.$i.'}{ref}}';
           	    $key_exp = $parent.'{arg{'.$i.'}{exp}}';
           	    $key_arg = $parent.'{arg{'.$i.'}';
            }
            
           	if(array_key_exists($key_val,$component)){
                $argDef = new ArgDefImpl();
                $argDef->setValue(trim($component[$key_val]));
                $componentDef->addArgDef($argDef);   
            }else if(array_key_exists($key_ref,$component)){
                $argDef = new ArgDefImpl();
                if(array_key_exists(trim($component[$key_ref]),$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[trim($component[$key_ref])],$argDef);
                }else{
                   $this->unresolvedCompRef_[trim($component[$key_ref])] = array($argDef);
                }
                $componentDef->addArgDef($argDef);               
            }else if(array_key_exists($key_exp,$component)){
                $argDef = new ArgDefImpl();
                $argDef->setExpression(trim($component[$key_exp]));
                $componentDef->addArgDef($argDef);               
            }
            
            if($argDef == null){
            	break;
            }else{
                $this->setupMetaDef($argDef,$component,$key_arg);
            }
        }
    }

    private function setupPropertyDef($componentDef,&$component){

        for($i=0;;$i++){
            $propertyDef = null;
            $key_name = 'property{'.$i.'}{name}';
            $key_val = 'property{'.$i.'}{val}';
            $key_ref = 'property{'.$i.'}{ref}';
            $key_exp = 'property{'.$i.'}{exp}';

           	if(array_key_exists($key_name,$component)){
               	if(array_key_exists($key_val,$component)){
                    $propertyDef = new PropertyDefImpl(trim($component[$key_name]));
                    $propertyDef->setValue(trim($component[$key_val]));
                    $componentDef->addPropertyDef($propertyDef);   
                }else if(array_key_exists($key_ref,$component)){
                    $propertyDef = new PropertyDefImpl(trim($component[$key_name]));
                    if(array_key_exists($component[$key_ref],$this->unresolvedCompRef_)){
                       array_push($this->unresolvedCompRef_[trim($component[$key_ref])],$propertyDef);
                    }else{
                       $this->unresolvedCompRef_[trim($component[$key_ref])] = array($propertyDef);
                    }
                    $componentDef->addPropertyDef($propertyDef);               
                }else if(array_key_exists($key_exp,$component)){
                    $propertyDef = new PropertyDefImpl(trim($component[$key_name]));
                    $propertyDef->setExpression(trim($component[$key_exp]));
                    $componentDef->addPropertyDef($propertyDef);               
               	}
           	}
           	
            if($propertyDef == null){
            	break;
            }else{
                $this->setupMetaDef($propertyDef,$component,'property{'.$i.'}');
            }
        }  
    }
        
    private function setupInitMethodDef($componentDef,&$component){

        for($i=0;;$i++){
            $initMethodDef = null;
            $key_name = 'init_method{'.$i.'}{name}';
            $key_exp = 'init_method{'.$i.'}{exp}';
            $key_arg = 'init_method{'.$i.'}';

            if(array_key_exists($key_exp,$component)){
                $initMethodDef = new InitMethodDefImpl();
                $initMethodDef->setExpression(trim($component[$key_exp]));
                $componentDef->addInitMethodDef($initMethodDef);
            }else if(array_key_exists($key_name,$component)){
                $initMethodDef = new InitMethodDefImpl(trim($component[$key_name]));
                $this->setupArgDef($initMethodDef,$component,$key_arg);
                $componentDef->addInitMethodDef($initMethodDef);
            }

            if($initMethodDef == null){
            	break;
            }
        }
    }

    private function setupDestroyMethodDef($componentDef,&$component){

        for($i=0;;$i++){
            $destroyMethodDef = null;
            $key_name = 'destroy_method{'.$i.'}{name}';
            $key_exp = 'destroy_method{'.$i.'}{exp}';
            $key_arg = 'destroy_method{'.$i.'}';

            if(array_key_exists($key_exp,$component)){
                $destroyMethodDef = new DestroyMethodDefImpl();
                $destroyMethodDef->setExpression(trim($component[$key_exp]));
                $componentDef->addDestroyMethodDef($destroyMethodDef);
            }else if(array_key_exists($key_name,$component)){
                $destroyMethodDef = new DestroyMethodDefImpl(trim($component[$key_name]));
                $this->setupArgDef($destroyMethodDef,$component,$key_arg);
                $componentDef->addDestroyMethodDef($destroyMethodDef);
            }

            if($destroyMethodDef == null){
            	break;
            }
        }
    }

    private function setupAspectDef($componentDef,&$component,$targetClassName){

        for($i=0;;$i++){
            $aspectDef = null;
            $key_pointcut = 'aspect{'.$i.'}{pointcut}';
            $key_ref = 'aspect{'.$i.'}{ref}';
            $key_exp = 'aspect{'.$i.'}{exp}';

            if(array_key_exists($key_ref,$component)){
                if(array_key_exists($key_pointcut,$component)){
                    $pointcuts = split(",",trim($component[$key_pointcut]));
                    $pointcut = new PointcutImpl($pointcuts);
                }else{
                    $pointcut = new PointcutImpl($targetClassName);
                }
                $aspectDef = new AspectDefImpl($pointcut);
                if(array_key_exists(trim($component[$key_ref]),$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[trim($component[$key_ref])],$aspectDef);
                }else{
                   $this->unresolvedCompRef_[trim($component[$key_ref])] = array($aspectDef);
                }
                $componentDef->addAspectDef($aspectDef);               
            }else if(array_key_exists($key_exp,$component)){
                if(array_key_exists($key_pointcut,$component)){
                    $pointcuts = split(",",trim($component[$key_pointcut]));
                    $pointcut = new PointcutImpl($pointcuts);
                }else{
                    $pointcut = new PointcutImpl($targetClassName);
                }
                $aspectDef = new AspectDefImpl($pointcut);
                $aspectDef->setExpression(trim($component[$key_exp]));
                $componentDef->addAspectDef($aspectDef);               
            }

            if($aspectDef == null){
            	break;
            }
        }
    }

    private function setupMetaDef($componentDef,&$component,$parent=""){

        for($i=0;;$i++){
            $metaDef = null;
        	if($parent==""){
                $key_name = 'meta{'.$i.'}{name}';
                $key_val = 'meta{'.$i.'}{val}';
                $key_ref = 'meta{'.$i.'}{ref}';
                $key_exp = 'meta{'.$i.'}{exp}';
        	}else{
                $key_name = $parent.'{meta{'.$i.'}{name}}';
                $key_val = $parent.'{meta{'.$i.'}{val}}';
                $key_ref = $parent.'{meta{'.$i.'}{ref}}';
                $key_exp = $parent.'{meta{'.$i.'}{exp}}';
                
                if(preg_match('/init_method/',$parent) or
                   preg_match('/destroy_method/',$parent)){
                    $key_name .= '}';
                    $key_val  .= '}';
                    $key_ref  .= '}';
                    $key_exp  .= '}';
                }
        	}

           	if(array_key_exists($key_val,$component)){
           		array_key_exists($key_name,$component) ?
           		    $metaDef = new MetaDefImpl(trim($component[$key_name])) :
           		    $metaDef = new MetaDefImpl();
                $metaDef->setValue(trim($component[$key_val]));
                $componentDef->addMetaDef($metaDef);   
            }else if(array_key_exists($key_ref,$component)){
           		array_key_exists($key_name,$component) ?
           		    $metaDef = new MetaDefImpl(trim($component[$key_name])) :
           		    $metaDef = new MetaDefImpl();
                if(array_key_exists($component[$key_ref],$this->unresolvedCompRef_)){
                   array_push($this->unresolvedCompRef_[trim($component[$key_ref])],$metaDef);
                }else{
                   $this->unresolvedCompRef_[trim($component[$key_ref])] = array($metaDef);
                }
                $componentDef->addMetaDef($metaDef);               
            }else if(array_key_exists($key_exp,$component)){
           		array_key_exists($key_name,$component) ?
           		    $metaDef = new MetaDefImpl(trim($component[$key_name])) :
           		    $metaDef = new MetaDefImpl();
                $metaDef->setExpression(trim($component[$key_exp]));
                $componentDef->addMetaDef($metaDef);               
           	}
           	
            if($metaDef == null){
            	break;
            }
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