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
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class UuCallAopProxyFactory {

    private function UuCallAopProxyFactory() {}
    
    /**
     * @param ReflectionClass 
     * @param array Interceptors 
     */
    static function create($targetClass,$map,$args,$params=null){
        $log = S2Logger::getLogger('UuCallAopProxyFactory');

        if(ClassUtil::hasMethod($targetClass,'__call')){
            $log->info("target class has __call(). ignore aspect.",__METHOD__);
            return ConstructorUtil::newInstance($targetClass,$args);
        }
               	
        $concreteClassName = 'UuCallAopProxy' . $targetClass->getName() . 'EnhancedByS2AOP';

        if(class_exists($concreteClassName,false)){
            return new $concreteClassName($targetClass,$map,$args,$params);
        }
        
        if(!$targetClass->isUserDefined()){
            return new UuCallAopProxy($targetClass,$map,$args,$params);
        }

        $classSrc = ClassUtil::getClassSource(new ReflectionClass('UuCallAopProxy'));

        $interfaces = ClassUtil::getInterfaces($targetClass); 
               
        if(count($interfaces) == 0){
            return new UuCallAopProxy($targetClass,$map,$args,$params);
        }

        $addMethodSrc = array();
        $interfaceNames = array();
        foreach ($interfaces as $interface){
            $interfaceSrc = ClassUtil::getSource($interface);
            $methods = $interface->getMethods();
            $unApplicable = false;
            foreach ($methods as $method){
                if($method->getDeclaringClass()->getName() == $interface->getName()){
                    if(AopProxy::isApplicableAspect($method)){
                        array_push($addMethodSrc,UuCallAopProxyFactory::getMethodDefinition($method,$interfaceSrc));
                    }else{
                        $unApplicable=true;	
                        break;
                    }
                }
            }
            if(!$unApplicable){
                array_push($interfaceNames,$interface->getName());
            }else{
                $log->info("interface [".$interface->getName()."] is unapplicable. not implemented.",__METHOD__);
            }
        }          

        if(count($interfaceNames)>0){
        	$implLine = " implements " . implode(',',$interfaceNames) . ' {';
        }else{
        	$implLine = ' {';
        }
        
        $srcLine = str_replace('UuCallAopProxy',$concreteClassName,$classSrc[0]);
        $srcLine = str_replace('{',$implLine,$srcLine);
        for($i=1;$i<count($classSrc)-1;$i++){
            $srcLine .= str_replace('UuCallAopProxy',$concreteClassName,$classSrc[$i]);
        }
        
        foreach($addMethodSrc as $methodSrc){
            $srcLine .= $methodSrc . "\n";
        }

        $srcLine .= "}\n";
        eval($srcLine);
        return new $concreteClassName($targetClass,$map,$args,$params);
    }
    
    private static function getMethodDefinition($refMethod,$interfaceSrc){

        $def = MethodUtil::getSource($refMethod,$interfaceSrc);        
        $defLine = trim(implode(' ',$def));
        $defLine = preg_replace("/\;$/","",$defLine);
        $defLine = preg_replace("/abstract\s/","",$defLine);
        $defLine .= " {";
        
        if(preg_match("/\((.*)\)/",$defLine,$regs)){
            $argLine = $regs[1];
        }
                
        $argsTmp = split('[ ,]',$argLine);
        $args = array();
        foreach($argsTmp as $item){
            if(preg_match('/^\$/',$item)){
                array_push($args,$item);
            }
            if(preg_match('/^\&(.+)/',$item,$regs)){
                array_push($args,$regs[1]);
            }
        }
        $argLine = implode(',',$args);
        $defLine .= ' return $this->__call(\'' . $refMethod->getName() .
                    '\',array(' . $argLine . ')); }';
        
        return $defLine;
    }
}
?>