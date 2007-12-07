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
// $Id:$
/**
 * @package org.seasar.framework.container
 * @author klove
 */
class S2ContainerApplicationContext {
    public  static $CLASSES = array();
    public  static $DICONS  = array();
    public  static $includePattern = array();
    public  static $excludePattern = array();
    public  static $autoAspects = array();

    private static $envPrefix    = null;
    private static $filterByEnv  = true;
    private static $commentCache = array();
    private static $readParentAnnotation = false;

    const COMPONENT_ANNOTATION = '@S2Component';
    const BINDING_ANNOTATION   = '@S2Binding';
    const ASPECT_ANNOTATION    = '@S2Aspect';
    const META_ANNOTATION      = '@S2Meta';
    const ANNOTATION_END_DELIMITER = '\s';

    public static function load($className) {
        if (isset(self::$CLASSES[$className])) {
            require_once(self::$CLASSES[$className]);
            return true;
        } else {
            return false;
        }
    }

    public static function init() {
        self::$CLASSES = array();
        self::$DICONS  = array();
        self::$includePattern = array();
        self::$excludePattern = array();
        self::$autoAspects    = array();
    }

    public static function import($path, $subDir = 0, $pear = false) {
        if (is_dir($path)) {
            self::scanDir($path, (int)$subDir, $pear, array());
        } else if (is_file($path)) {
            self::importInternal($path);
        } else {
            trigger_error(__CLASS__ . " : invalid args. [$path]", E_USER_WARNING);
        }
    }

    private static function scanDir($parentPath, $subDir, $pear, $pkgs = array()) {
        $entries = scandir($parentPath);
        foreach($entries as $entry) {
            if (preg_match('/^\./', $entry)) {
                continue;
            }
            $path = $parentPath . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($path)) {
                if ($subDir === -1 or $subDir > count($pkgs)) {
                    $pkgs[] = $entry;
                    self::scanDir($path, $subDir, $pear, $pkgs);
                    array_pop($pkgs);
                    //self::scanDir($path, $subDir, $pear, array_merge($pkgs, array($entry)));
                }
            } else if(is_file($path)) {
                self::importInternal($path, $pear, $pkgs);
            }
        }
    }

    public static function importInternal($filePath, $pear = false, $pkgs = array()){
        $fileName = basename($filePath);
        if (preg_match("/([^\.]+).+php$/", $fileName, $matches)) {
            if ($pear === true) {
                $pkgs[] = $matches[1];
                $className = implode('_', $pkgs);
            } else { 
                $className = $matches[1];
            }
            self::$CLASSES[$className] = $filePath;
            S2Container_S2Logger::getLogger(__CLASS__)->debug("find class $className : $filePath", __METHOD__);
        } else if (preg_match('/\.dicon$/', $fileName)) {
            self::$DICONS[$fileName] = $filePath;
            S2Container_S2Logger::getLogger(__CLASS__)->debug("find dicon $fileName : $filePath", __METHOD__);
        } else {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("ignore file $fileName : $filePath", __METHOD__);
        }
    }

    public static function create($cacheKey = null) {
        $dicons  = self::filter(array_values(self::$DICONS));
        $classes = self::filter(array_keys(self::$CLASSES));
        $classes = self::envFilter($classes);

        if (count($dicons) == 0 and count($classes) == 0) {
            S2Container_S2Logger::getLogger(__CLASS__)->info("dicon, class not found at all. create empty container.", __METHOD__);
            return new S2ContainerImpl();
        }

        $support = S2Container_CacheSupportFactory::create();
        if ($cacheKey === null) {
            $cacheKey = implode(',', array_merge($dicons, $classes));
        }
        if (!$support->isContainerCaching($cacheKey)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("set caching off.",__METHOD__);
            return self::createInternal($dicons, $classes);
        }

        if ($data = $support->loadContainerCache($cacheKey)) {
            S2Container_S2Logger::getLogger(__CLASS__)->
                    info("cached container found.",__METHOD__);
            $container = unserialize($data);
            if (is_object($container) and 
                $container instanceof S2Container) {
                $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);
                return $container;
            } else {
                 throw new S2Container_S2RuntimeException('ESSR0017', array('invalid cache found.'));
            }
        }
        else {
            S2Container_S2Logger::getLogger(__CLASS__)->
                info("create container and cache it.",__METHOD__);
            $container = self::createInternal($dicons, $classes);
            $support->saveContainerCache(serialize($container), $cacheKey);
            return $container;
        }
    }

    public static function createInternal($dicons, $classes) {
        $container = new S2ContainerImpl();
        foreach ($dicons as $dicon) {
            $child = S2ContainerFactory::includeChild($container,$dicon);
            $child->setRoot($container->getRoot());
            S2Container_S2Logger::getLogger(__CLASS__)->debug("include dicon : $dicon", __METHOD__);
        }

        S2Container_ChildComponentDefBindingUtil::init();
        foreach($classes as $className) {
            $cd = self::createComponentDef($className);
            if ($cd === null) {
                continue;
            }
            $container->register($cd);
            self::setupComponentDef($cd, $className);
            S2Container_S2Logger::getLogger(__CLASS__)->debug("register component : $className", __METHOD__);
        }
        S2Container_ChildComponentDefBindingUtil::bind($container);

        return $container;
    }

    public static function createComponentDef($className) {
        $refClass = new ReflectionClass($className);
        if (!self::hasAnnotation($refClass, self::COMPONENT_ANNOTATION)) {
            return new S2Container_ComponentDefImpl($refClass);
        }

        $componentInfo = self::getAnnotation($refClass, self::COMPONENT_ANNOTATION);
        if (isset($componentInfo['available']) and (boolean)$componentInfo['available'] === false) {
            return null;
        }
        
        if (isset($componentInfo['name'])) {
            $cd = new S2Container_ComponentDefImpl($refClass, $componentInfo['name']);
        } else {
            $cd = new S2Container_ComponentDefImpl($refClass);
        }
        if (isset($componentInfo['instance'])) {
            $cd->setInstanceMode($componentInfo['instance']);
        }
        if (isset($componentInfo['autoBinding'])) {
            $cd->setAutoBindingMode($componentInfo['autoBinding']);
        }
        
        return $cd;
    }

    public static function setupComponentDef(S2Container_ComponentDef $cd, $className) {
        $classRef = $cd->getComponentClass();
        $methodRefs = $classRef->getMethods();
        foreach ($methodRefs as $methodRef) {
            if (self::$readParentAnnotation === false and 
                $methodRef->getDeclaringClass()->getName() !== $classRef->getName()) {
                continue;
            }
            if (!$methodRef->isPublic() or 
                $methodRef->isConstructor() or
                preg_match('/^_/', $methodRef->getName()) ) {
                continue;
            }
            $matches = array();
            if (preg_match('/^set(.+)/', $methodRef->getName(), $matches) and
                self::hasAnnotation($methodRef, self::BINDING_ANNOTATION)) {
                self::setupPropertyDef($cd, $methodRef, $matches[1]);
            }
            if (!$methodRef->isStatic() and 
                !$methodRef->isFinal() and
                self::hasAnnotation($methodRef, self::ASPECT_ANNOTATION)) {
                self::setupMethodAspectDef($cd, $methodRef);
            }
        }
        if (!$classRef->isFinal() and
            self::hasAnnotation($classRef, self::ASPECT_ANNOTATION)) {
            self::setupClassAspectDef($cd, $classRef);
        }

        if (self::hasAnnotation($cd->getComponentClass(), self::META_ANNOTATION)) {
            self::setupClassMetaDef($cd, $classRef);
        }

        foreach(self::$autoAspects as $aspectInfo) {
            if (preg_match($aspectInfo['componentPattern'], $cd->getComponentName()) or
                preg_match($aspectInfo['componentPattern'], $cd->getComponentClass()->getName())) {
                self::setupAspectDef($cd, $aspectInfo);
            }
        }

        return $cd;
    }

    private static function setupPropertyDef(S2Container_ComponentDef $cd, ReflectionMethod $methodRef, $propName) {
        $propInfo = self::getAnnotation($methodRef, self::BINDING_ANNOTATION);
        if (isset($propInfo[0])) {
            $propName = strtolower(substr($propName, 0, 1)) . substr($propName, 1);
            $propertyDef = new S2Container_PropertyDefImpl($propName);
            $cd->addPropertyDef($propertyDef);
            $propertyDef->setExpression($propInfo[0]);
            S2Container_ChildComponentDefBindingUtil::put($propInfo[0], $propertyDef);
        } else {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("binding annotation found. cannot get values.", __METHOD__);
        }
    }

    private static function setupClassAspectDef(S2Container_ComponentDef $cd, $classRef) {
        $annoInfo = self::getAnnotation($classRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        self::setupAspectDef($cd, $annoInfo);
    }

    private static function setupMethodAspectDef(S2Container_ComponentDef $cd, ReflectionMethod $methodRef) {
        $annoInfo = self::getAnnotation($methodRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("method aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        $annoInfo['pointcut'] = '^' . $methodRef->getName() . '$';
        self::setupAspectDef($cd, $annoInfo);
    }

    private static function setupAspectDef(S2Container_ComponentDef $cd, array $annoInfo) {
        if (isset($annoInfo['interceptor'])) {
            if (isset($annoInfo['pointcut'])) {
                $pointcut = new S2Container_PointcutImpl(preg_split('/,/', $annoInfo['pointcut']));
            } else {
                $pointcut = new S2Container_PointcutImpl($cd->getComponentClass());
            }
            $aspectDef = new S2Container_AspectDefImpl($pointcut);
            $cd->addAspectDef($aspectDef);
            $aspectDef->setExpression($annoInfo['interceptor']);
            S2Container_ChildComponentDefBindingUtil::put($annoInfo['interceptor'], $aspectDef);
        } else {
            throw new S2Container_S2RuntimeException('ESSR0017', array("invalid aspect info. interceptor not found. [{$cd->getComponentClass()->getName()} @S2Aspect]"));
        }
    }

    private static function setupClassMetaDef(S2Container_ComponentDef $cd, ReflectionClass $classRef) {
        $annoInfo = self::getAnnotation($classRef, self::META_ANNOTATION);
        if (count($annoInfo) === 0) {
            S2Container_S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }

        foreach($annoInfo as $key => $val) {
            $metaDef = new S2Container_MetaDefImpl($key);
            $cd->addMetaDef($metaDef);
            $metaDef->setExpression($val);
            S2Container_ChildComponentDefBindingUtil::put($val,$metaDef);
        }
    }

    public static function hasAnnotation($refClazz, $annoName) {
        return preg_match("/$annoName\s*[\(]/s", $refClazz->getDocComment()) === 0 ? false : true;
    }

    public static function getAnnotation($refClazz, $annoName) {
        $key = $refClazz instanceof ReflectionMethod ? 
               $refClazz->getDeclaringClass()->getName() . '::' . $refClazz->getName() . ' method':
               $refClazz->getName() . ' class';

        if (isset(self::$commentCache[$key])) {
            $comment = self::$commentCache[$key];
            S2Container_S2Logger::getLogger(__CLASS__)->debug("comment cache hit : $key [$comment]", __METHOD__);
        } else {
            $comment = self::formatCommentLine($refClazz->getDocComment());
            self::$commentCache[$key] = $comment;
        }

        $matches = array();
        $regex = "/$annoName\s*(\(.+?\))" . self::ANNOTATION_END_DELIMITER . '/';
        if (preg_match($regex, $comment, $matches)) {
            $cmd = S2Container_EvalUtil::getExpression('array' . $matches[1]);
            if(defined('S2CONTAINER_PHP5_DEBUG_EVAL') and S2CONTAINER_PHP5_DEBUG_EVAL){
                S2Container_S2Logger::getLogger(__CLASS__)->debug("[ $cmd ]",__METHOD__);
            }
            $annoInfo = eval($cmd);
            S2Container_S2Logger::getLogger(__CLASS__)->debug("annotation found : $key $annoName : " . print_r($annoInfo, true), __METHOD__);
            return $annoInfo;
        }
        return array();
    }

    public static function formatCommentLine($commentLine) {
        $comments = preg_split('/[\n\r]/', $commentLine);
        $comment = ' ';
        $o = count($comments);
        for ($i=0; $i<$o; $i++) {
            $line = preg_replace('/^\/\*+/', '', trim($comments[$i]));
            $line = preg_replace('/\*+\/$/', '', trim($line));
            $line = preg_replace('/^\**/',   '', trim($line));
            $comment .= $line . ' ';
        }
        return $comment;
    }

    public static function envFilter($items) {
        $envPrefix = null;
        if (self::$filterByEnv and defined('S2CONTAINER_PHP5_ENV')) {
            if (self::$envPrefix === null) {
                $envPrefix = ucfirst(strtolower(S2CONTAINER_PHP5_ENV));
            } else {
                $envPrefix = ucfirst(strtolower(self::$envPrefix));
            }
        } else {
            return $items;
        }
        $classes = array();
        $c = count($items);
        for ($i=0; $i<$c; $i++) {
            $envClassName = $envPrefix . $items[$i];
            if (in_array($envClassName, $items)) {
                continue;
            } else {
                $classes[] = $items[$i];
            }
        }
        return $classes;
    }

    public static function filter($items) {
        $includePatternCount = count(self::$includePattern);
        if ($includePatternCount > 0) {
            $includes = array();
            $o = count($items);
            for($i=0; $i<$o; $i++) {
                for($j=0; $j<$includePatternCount; $j++){
                    if (preg_match(self::$includePattern[$j], $items[$i])) {
                        $includes[] = $items[$i];
                        break;
                    }
                }
            }
            $items = $includes;
        }

        $excludePatternCount = count(self::$excludePattern);
        if ($excludePatternCount === 0) {
            return $items;
        }

        $includes = array();
        $o = count($items);
        for($i=0; $i<$o; $i++) {
            $matched = false;
            for($j=0; $j<$excludePatternCount; $j++){
                if (preg_match(self::$excludePattern[$j], $items[$i])) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $includes[] = $items[$i];
            }
        }
        return $includes;
    }

    public static function addIncludePattern($pattern) {
        self::$includePattern = array_merge(self::$includePattern, (array)$pattern);
    }

    public static function getIncludePattern() {
        return self::$includePattern;
    }

    public static function setIncludePattern($pattern = array()) {
        self::$includePattern = (array)$pattern;
    }

    public static function addExcludePattern($pattern) {
        self::$excludePattern = array_merge(self::$excludePattern, (array)$pattern);
    }

    public static function getExcludePattern() {
        return self::$excludePattern;
    }

    public static function setExcludePattern($pattern = array()) {
        self::$excludePattern = (array)$pattern;
    }

    public static function setFilterByEnv($val = true) {
        self::$filterByEnv = $val;
    }

    public static function setEnvPrefix($val = null) {
        self::$envPrefix = $val;
    }

    public static function setReadParentAnnotation($val = true) {
        self::$readParentAnnotation = $val;
    }

    public static function registerAspect($componentPattern, $interceptor, $pointcut = null) {
        if ($pointcut == null) {
            $aspectInfo = array('componentPattern' => $componentPattern,
                                'interceptor'      => $interceptor);
        } else {
            $aspectInfo = array('componentPattern' => $componentPattern,
                                'interceptor'      => $interceptor,
                                'pointcut'         => $pointcut);
        }
        self::$autoAspects[] = $aspectInfo;
    }
}
?>
