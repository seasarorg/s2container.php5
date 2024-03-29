<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
class ComponentInfoDef {
    public static $USE_PHP_NAMESPACE = false;
    private $reflectionClass = null;
    private $className = null;
    private $name = null;
    private $instance = null;
    private $autoBinding = null;
    private $namespace = null;
    private $usePhpNamespace = null;
    private $constructClosure = null;

    /**
     * @param string $className
     */
    public function __construct($clazz = null) {
        if ($clazz instanceof \ReflectionClass) {
            $this->reflectionClass = $clazz;
            $this->className = $clazz->getName();
        } else {
            $this->className = $clazz;
        }
        $this->usePhpNamespace = self::$USE_PHP_NAMESPACE;
    }

    /**
     * @param string $className
     * @return seasar\container\ComponentInfoDef
     */
    public function setReflectionClass($calzz) {
        $this->reflectionClass = $clazz;
        $this->className = $clazz->getName();
        return $this;
    }

    /**
     * @return string
     */
    public function getReflectionClass() {
        if (is_null($this->reflectionClass)) {
            $this->reflectionClass = new \ReflectionClass($this->className);
        }
        return $this->reflectionClass;
    }

    /**
     * @param string $className
     * @return seasar\container\ComponentInfoDef
     */
    public function setClassName($className) {
        $this->className = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * @return boolean
     */
    public function hasName() {
        return !is_null($this->name);
    }

    /**
     * @param string $name
     * @return seasar\container\ComponentInfoDef
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function hasInstance() {
        return !is_null($this->instance);
    }

    /**
     * @param string $instance
     * @return seasar\container\ComponentInfoDef
     */
    public function setInstance($instance) {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstance() {
        return $this->instance;
    }

    /**
     * @return boolean
     */
    public function hasAutoBinding() {
        return !is_null($this->autoBinding);
    }

    /**
     * @param string $autoBinding
     * @return seasar\container\ComponentInfoDef
     */
    public function setAutoBinding($autoBinding) {
        $this->autoBinding = $autoBinding;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoBinding() {
        return $this->autoBinding;
    }

    /**
     * @return boolean
     */
    public function hasNamespace() {
        return !is_null($this->namespace);
    }

    /**
     * @param string $namespace
     * @return seasar\container\ComponentInfoDef
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace() {
        if (is_null($this->namespace)) {
            if ($this->usePhpNamespace === true) {
                $ns = $this->getReflectionClass()->getNamespaceName();
                $this->namespace = str_replace('\\', '.', $ns);
            }
        }
        return $this->namespace;
    }

    /**
     * @param boolean $val
     * @return seasar\container\ComponentInfoDef
     */
    public function usePhpNamespace($val = true) {
        $this->usePhpNamespace = $val;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isUsePhpNamespace() {
        return $this->usePhpNamespace;
    }

    /**
     * @param \Closure $constructClosure
     * @return seasar\container\ComponentInfoDef
     */
    public function setConstructClosure(\Closure $constructClosure) {
        $this->constructClosure = $constructClosure;
        return $this;
    }

    /**
     * @see seasar\container\ComponentInfoDef::setConstructClosure()
     */
    public function construct(\Closure $constructClosure) {
        return $this->setConstructClosure($constructClosure);
    }

    /**
     * @return \Closure
     */
    public function getConstructClosure() {
        return $this->constructClosure;
    }

    /**
     * @return boolean
     */
    public function hasConstructClosure() {
        return !is_null($this->constructClosure);
    }
}
