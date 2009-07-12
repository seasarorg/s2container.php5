<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
/**
 * S2Containerの実装クラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class S2ContainerImpl implements \seasar\container\S2Container {
    /**
     * @var array
     */
    private $componentDefMap  = array();

    /**
     * @var array
     */
    private $componentDefList = array();

    /**
     * @var array
     */
    private $children    = array();

    /**
     * @var array
     */
    private $descendants = array();

    /**
     * @var string
     */
    private $namespace      = null;

    /**
     * @var \seasar\container\util\MetaDefSupport
     */
    private $metaDefSupport = null;

    /**
     * @var string
     */
    private $path      = null;

    /**
     * @var \seasar\container\S2Container
     */
    private $root      = null;

    /**
     * S2ContainerImpl を構築します。
     */
    public function __construct() {
        $this->metaDefSupport = new \seasar\container\util\MetaDefSupport();
        $this->root = $this;

        $componentDef = new SimpleComponentDef($this, \seasar\container\Config::CONTAINER_NAME);
        $this->componentDefMap[\seasar\container\Config::CONTAINER_NAME] = $componentDef;
        $this->componentDefMap['\seasar\container\S2Container'] = $componentDef;
    }

    /**
     * ルートのS2コンテナを返します。
     *
     * @return seasar::contaienr::S2Container
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * ルートのS2コンテナを設定します。
     *
     * @param seasar::contaienr::S2Container $root
     */
    public function setRoot(\seasar\container\S2Container $root) {
        $this->root = $root;
    }
        
    /**
     * @see seasar::contaienr::S2Container::getComponent()
     */
    public function getComponent($componentKey) {
        return $this->getComponentDef($componentKey)->getComponent();
    }

    /**
     * @see seasar::contaienr::S2Container::findComponents()
     */
    public function findComponents($componentKey) {
        $componentDefs = $this->findComponentDefs($componentKey);
        $components = array();
        foreach ($componentDefs as $componentDef) {
            $components[] = $componentDef->getComponent();
        }
        return $components;
    }

    /**
     * @see seasar::contaienr::S2Container::register()
     */
    public function register($component, $componentName = '') {
        if ($component instanceof \seasar\container\ComponentDef) {
            $this->registerInternal($component);
            $this->componentDefList[] = $component;
        } else if (is_object($component)) {
            $this->register(new SimpleComponentDef($component, $componentName));
        } else {
            $this->register(new ComponentDefImpl($component, $componentName));
        }
    }

    /**
     * ComponentDefを登録します。
     *
     * @param seasar::contaienr\ComponentDef
     */
    private function registerInternal(\seasar\container\ComponentDef $componentDef) {
        if ($componentDef->getContainer() === null) {
            $componentDef->setContainer($this);
        }
        $this->registerByClass($componentDef);
        $this->registerByName($componentDef);
    }

    /**
     * クラス名をキーにして ComponentDefを登録します。
     *
     * @param seasar::contaienr\ComponentDef
     */
    private function registerByClass(\seasar\container\ComponentDef $componentDef) {
        $classes = $this->getAssignableClasses($componentDef->getComponentClass());
        $componentName = $componentDef->getComponentName();
        foreach ($classes as $namespacedClassName) {
            if ($namespacedClassName !== $componentName) {
                $this->registerMap($namespacedClassName, $componentDef);
                $className = \seasar\util\ClassUtil::getClassName($namespacedClassName);
                if ($className !== $namespacedClassName and $className !== $componentName) {
                    $this->registerMap($className, $componentDef);
                }
                $lcClassName = \seasar\util\StringUtil::lcfirst($className);
                if ($lcClassName !== $className and $lcClassName !== $namespacedClassName and $lcClassName !== $componentName) {
                    $this->registerMap($lcClassName, $componentDef);
                }
            }
        }
    }

    /**
     * 名前をキーにして ComponentDefを登録します。
     *
     * @param seasar::contaienr\ComponentDef
     */
    private function registerByName(\seasar\container\ComponentDef $componentDef) {
        $componentName = $componentDef->getComponentName();
        if ($componentName !== null) {
            $this->registerMap($componentName, $componentDef);
        }
    }

    /**
     * キャッシュに ComponentDefを登録します。
     *
     * @param string $key
     * @param \seasar\container\ComponentDef
     */
    private function registerMap($key, \seasar\container\ComponentDef $componentDef) {
        if (array_key_exists($key, $this->componentDefMap)) {
            $this->processTooManyRegistration($key, $componentDef);
        } else {
            $this->componentDefMap[$key] = $componentDef;
        }
    }

    /**
     * @see \seasar\container\S2Container::getComponentDefSize()
     */
    public function getComponentDefSize() {
        return count($this->componentDefList);
    }

    /**
     * @see \seasar\container\S2Container::getComponentDef()
     */
    public function getComponentDef($key) {
        if (is_int($key)) {
            if (!isset($this->componentDefList[$key])) {
                throw new \seasar\container\exception\ComponentNotFoundRuntimeException($key);
            }
            return $this->componentDefList[$key];
        }
        if (is_object($key)) {
            $key = get_class($key);
        }

        $componentDef = $this->getComponentDefInternal($key);
        if ($componentDef === null) {
            throw new \seasar\container\exception\ComponentNotFoundRuntimeException($key);
        }
        return $componentDef;
    }

    /**
     * @see \seasar\container\S2Container::findComponentDefs()
     */
    public function findComponentDefs($key)
    {
        $componentDef = $this->getComponentDefInternal($key);
        if ($componentDef === null) {
            return array();
        } else if ($componentDef instanceof TooManyRegistrationComponentDef) {
            return $componentDef->getComponentDefs();
        }
        return array($componentDef);
    }

    /**
     * 内部的なgetComponentDefの実装です。
     *
     * @param string $key
     */
    private function getComponentDefInternal($key) {
        if (array_key_exists($key, $this->componentDefMap)) {
            return $this->componentDefMap[$key];
        }

        $matches = array();
        if (preg_match('/(.+?)' . \seasar\container\Config::NS_SEP . '(.+)/', $key, $matches)) {
            if ($this->hasComponentDef($matches[1])) {
                $childContainer = $this->getComponent($matches[1]);
                if ($childContainer instanceof \seasar\container\S2Container &&
                    $childContainer->hasComponentDef($matches[2])) {
                    return $childContainer->getComponentDef($matches[2]);
                }
            }
        } else {
            foreach ($this->children as $childContainer) {
                if ($childContainer->hasComponentDef($key)) {
                    return $childContainer->getComponentDef($key);
                }
            }
        }

        if (class_exists($key) || interface_exists($key)) {
            $refClass = new \ReflectionClass($key);
            if ($refClass->isUserDefined()) {
                $cd = \seasar\container\factory\ComponentDefBuilder::create($this, $refClass);
                if ($refClass->isAbstract() || $refClass->isInterface()) {
                    if (count($cd->getAspectDefs()) === 0) {
                        return null;
                    }
                }
                $this->register($cd);
                return $cd;
            }
        }
        return null;
    }

    /**
     * @see \seasar\container\S2Container::hasComponentDef()
     */
    public function hasComponentDef($componentKey) {
        return $this->getComponentDefInternal($componentKey) !== null;
    }

    /**
     * @see \seasar\container\S2Container::hasDescendant()
     */
    public function hasDescendant($path) {
        return array_key_exists($path, $this->descendants);
    }
    
    /**
     * pathを読み込んだS2コンテナを返します。
     *
     * @param string path
     * @return \seasar\container\S2Container
     */
    public function getDescendant($path) {
        if ($this->hasDescendant($path)) {
            return $this->descendants[$path];
        } else {
            throw new \seasar\container\exception\ContainerNotRegisteredRuntimeException($path);
        }
    }

    /**
     * descendantを子孫コンテナとして登録します。
     * 子孫コンテナとは、このコンテナに属する子のコンテナや、その子であるコンテナです。 
     *
     * @param \seasar\container\S2Container $descendant
     */
    public function registerDescendant(\seasar\container\S2Container $descendant) {
        $this->descendants[$descendant->getPath()] = $descendant;
    }

    /**
     * コンテナを子としてインクルードします。
     *
     * @see \seasar\container\S2Container::includeChild()
     */
    public function includeChild(\seasar\container\S2Container $childContainer) {
        $childContainer->setRoot($this->getRoot());
        $this->children[] = $childContainer;
        $this->registerDescendant($childContainer);
        $ns = $childContainer->getNamespace();
        if ($ns !== null) {
            $this->registerMap($ns, new S2ContainerComponentDef($childContainer, $ns));
        }
    }

    /**
     * インクルードしている子コンテナの数を返します。
     *
     * @see \seasar\container\S2Container::getChildSize()
     */
    public function getChildSize() {
        return count($this->children);
    }

    /**
     * @see \seasar\container\S2Container::getChild()
     */
    public function getChild($index) {
        if (!isset($this->children[$index])) {
            throw new \seasar\container\exception\ContainerNotRegisteredRuntimeException("Child:" . $index);
        }
        return $this->children[$index];
    }

    /**
     * @see \seasar\container\S2Container::getNamespace()
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @see \seasar\container\S2Container::setNamespace()
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        $this->componentDefMap[$this->namespace] = new SimpleComponentDef($this, $this->namespace);
    }

    /**
     * 設定ファイルのpathを返します。
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * 設定ファイルのpathを設定します。
     *
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @see \seasar\container\util\MetaDefAware::addMetaDef()
     */
    public function addMetaDef(MetaDef $metaDef) {
        $this->metaDefSupport->addMetaDef($metaDef);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDef()
     */
    public function getMetaDef($name) {
        return $this->metaDefSupport->getMetaDef($name);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name) {
        return $this->metaDefSupport->getMetaDefs($name);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize() {
        return $this->metaDefSupport->getMetaDefSize();
    }
    
    /**
     * すべての親クラスと実装しているすべてのインターフェースを返します。
     *
     * @param \ReflectionClass $componentClass
     * @param array 
     */
    private function getAssignableClasses(\ReflectionClass $componentClass) {
        $classes = array();
        $interfaces = \seasar\util\ClassUtil::getInterfaces($componentClass);
        foreach ($interfaces as $interface) {
            $classes[] = $interface->getName();
        }

        $reflection = $componentClass;
        if(!$reflection->isInterface()){
            while ($reflection instanceof \ReflectionClass) {
                $classes[] = $reflection->getName();
                $reflection = $reflection->getParentClass();
            }
        }
        return $classes;
    }

    /**
     * 登録済みのキーに対してコンポーネントを登録した際に、TooManyRegistrationComponentDefを構築します。
     * @param string $key
     * @param \seasar\container\ComponentDef $newComponentDef
     */
    private function processTooManyRegistration($key, \seasar\container\ComponentDef $newComponentDef) {
        $componentDef = $this->componentDefMap[$key];
        if ($componentDef instanceof \seasar\container\impl\TooManyRegistrationComponentDef) {
            $componentDef->addComponentDef($newComponentDef);
        } else {
            $tmrcf = new \seasar\container\impl\TooManyRegistrationComponentDef($key);
            $tmrcf->addComponentDef($componentDef);
            $tmrcf->addComponentDef($newComponentDef);
            $this->componentDefMap[$key] = $tmrcf;
        }
    }
}
