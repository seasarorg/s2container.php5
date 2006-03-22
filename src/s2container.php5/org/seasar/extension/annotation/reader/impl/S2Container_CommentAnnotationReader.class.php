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
 * @package org.seasar.extension.annotation.reader.impl
 * @author klove
 */
class S2Container_CommentAnnotationReader 
    implements S2Container_AnnotationReader
{
    public function __construct() {}
    
    public function getAnnotations(ReflectionClass $clazz,
                                   $methodName){

        if(is_string($methodName)){
            $clazz = $clazz->getMethod($methodName);
        }

        $comments = preg_split("/[\n\r]/",$clazz->getDocComment(),-1,PREG_SPLIT_NO_EMPTY);
        $inAnno = false;
        $annoLines = array();
        $annoObjects = array();
        foreach($comments as $line){
            $line = $this->removeCommentSlashAster($line);
            if(preg_match("/^@\w+$/",$line) or
               preg_match("/^@\w+\s*\(/",$line)){
                $inAnno = true;
                if(count($annoLines) != 0){
                    $annoObj = $this->getAnnotationObject($annoLines);
                    if(is_object($annoObj)){
                        $annoObjects[get_class($annoObj)] = $annoObj;
                    }
                    $annoLines = array();
                }
            }
            if($inAnno){
                $annoLines[] = $line;
            }
        }

        if(count($annoLines) != 0){
            $annoObj = $this->getAnnotationObject($annoLines);
            if(is_object($annoObj)){
                $annoObjects[get_class($annoObj)] = $annoObj;
            }
        }

        if(count($annoObjects) > 0){
            return $annoObjects;
        }
        return null;
    }

    private function getAnnotationObject($annoLines){
        if(preg_match("/^@(\w+)$/",$annoLines[0],$matches)){
            return S2Container_AnnotationFactory::create($matches[1]);
        }
        
        if(preg_match("/^@\w+\s*\(/",$annoLines[0])){
            $line  = implode(" ", $annoLines);
            if(preg_match("/^@(\w+)\s*\((.*)\)/",$line,$matches)){

                if(trim($matches[2]) == ''){
                    return S2Container_AnnotationFactory::create($matches[1]);
                }
                $annotationType = $matches[1];
                $items = preg_split("/,/",$matches[2]);
                $argType = null;
                $args = array();
                foreach($items as $item){
                    if(preg_match("/^(.+?)=(.+)/s",$item,$matches)){
                        if($argType == S2Container_AnnotationFactory::ARGS_TYPE_ARRAY){
                            throw new S2Container_AnnotationRuntimeException('ERR003',array($line,$item));
                        }
                        $key = $this->removeQuote($matches[1]);
                        $val = $this->removeQuote($matches[2]);
                        
                        if($key == ""){
                            throw new S2Container_AnnotationRuntimeException('ERR004',array($line,$item));
                        }
                        $args[$key] = $val;
                        $argType = S2Container_AnnotationFactory::ARGS_TYPE_HASH;
                    }else{
                        if($argType == S2Container_AnnotationFactory::ARGS_TYPE_HASH){
                            throw new S2Container_AnnotationRuntimeException('ERR003',array($line,$item));
                        }
                        $item = $this->removeQuote($item);
                        $args[] = $item;
                        $argType = S2Container_AnnotationFactory::ARGS_TYPE_ARRAY;
                    }
                }

                return S2Container_AnnotationFactory::create($annotationType,
                                                             $args,
                                                             $argType);
            }else{
                $line = implode(" ",$annoLines);
                S2Container_S2Logger::getLogger(__CLASS__)->
                    info("ignored : [ $line ]",__METHOD__);               
            }
        }

        return null;
    }

    private function removeQuote($str){
        $str = trim($str);
        $str = preg_replace("/^[\"']/",'',$str);
        $str = preg_replace("/[\"']$/",'',$str);
        return trim($str);
    }

    private function removeCommentSlashAster($line){
        $line = trim($line);
        $line = preg_replace("/^\/\*\*/","",$line);
        $line = preg_replace("/\*\/$/","",$line);
        $line = preg_replace("/^\*/","",$line);
        return trim($line);
    }
}
?>