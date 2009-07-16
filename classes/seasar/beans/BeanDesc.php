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
 *   - アクセス修飾子がpublicなプロパティを管理します。
 *   - アクセス修飾子がpublicでタイプヒントされたプロパティを管理します。
 *   - セッターメソッドを管理します。
 *   - タイプヒントされたセッターメソッドを管理します。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar\beans;
class BeanDesc {

    /**
     * @var \ReflectionClass
     */
    private $beanClass = null;

    /**
     * @var array
     */
    private $propertyDescs = array();

    /**
     * @var array
     */
    private $typehintPropertyDescs = array();

    /**
     * BeanDescを構築します。
     *
     * @param string $className
     */
    public function __construct(\ReflectionClass $clazz) {
        $this->beanClass = $clazz;
        $this->setupAccessorMethodPropertyDescs();
    }

    /**
     * BeanクラスのReflectionClassを返します。
     *
     * @return \ReflectionClass
     */
    public function getBeanClass(){
        return $this->beanClass;
    }

    /**
     * PropertyDescをすべて返します。
     *
     * @return array
     */
    public function getPropertyDescs() {
        return $this->propertyDescs;
    }

    /**
     * PropertyDesc数を返します
     *
     * @return integer
     */
    public function getPropertyDescSize() {
        return count($this->propertyDescs);
    }

    /**
     * 指定されたプロパティ名についてPropertyDescが存在するかを返します。
     *
     * @param string $name
     * @return boolean
     */
    public function hasPropertyDesc($name) {
        return array_key_exists($name, $this->propertyDescs);
    }

    /**
     * 指定されたプロパティ名についてPropertyDescを返します。
     *
     * @param string $name
     * @return \seasar\beans\AbstractPropertyDesc
     * @throw \seasar\exception\PropertyNotFoundRuntimenException
     */
    public function getPropertyDesc($name) {
        if ($this->hasPropertyDesc($name)) {
            return $this->propertyDescs[$name];
        }
        throw new \seasar\exception\PropertyNotFoundRuntimeException($this->beanClass, $name);
    }

    /**
     * タイプヒントされているPropertDescをすべて返します。
     *
     * @return array
     */
    public function getTypehintPropertyDescs() {
        return $this->typehintPropertyDescs;
    }

    /**
     * タイプヒントされているPropertyDesc数を返します
     *
     * @return integer
     */
    public function getTypehintPropertyDescSize() {
        return count($this->typehintPropertyDescs);
    }

    /**
     * 指定されたプロパティ名について、タイプヒントされているPropertyDescが存在するかを返します。
     *
     * @param string $name
     * @return boolean
     */
    public function hasTypehintPropertyDesc($name) {
        return array_key_exists($name, $this->typehintPropertyDescs);
    }

    /**
     * 指定されたプロパティ名について、タイプヒントされたPropertyDescを返します。
     *
     * @param string $name
     * @return \seasar\beans\AbstractPropertyDesc
     * @throw \seasar\exception\PropertyNotFoundRuntimenException
     */
    public function getTypehintPropertyDesc($name) {
        if ($this->hasTypehintPropertyDesc($name)) {
            return $this->typehintPropertyDescs[$name];
        }
        throw new \seasar\exception\PropertyNotFoundRuntimeException($this->beanClass, $name);
    }

    /**
     * アクセッサメソッドをすべて取得します。
     * また、タイプヒントされているアクセッサメソッドをすべて取得します。
     */
    private function setupAccessorMethodPropertyDescs() {
        $methods = $this->beanClass->getMethods();
        foreach($methods as $method) {
            $params = $method->getParameters();
            $methodName = $method->getName();
            if ($method->isPublic() and
                count($params) === 1 and 
                0 === strpos($methodName, 'set')) {
                $propName = \seasar\util\StringUtil::lcfirst(substr($methodName, 3));
                $propertyDesc = new AccessorMethodPropertyDesc($this->beanClass, $propName);
                $this->propertyDescs[$propName] = $propertyDesc;

                if ($params[0]->getClass() === null) {
                    $propertyDesc->setArrayAcceptable($params[0]->isArray());
                } else {
                    $propertyDesc->setTypehint($params[0]->getClass()->getName());
                    $propertyDesc->setTypehintClass($params[0]->getClass());
                    $this->typehintPropertyDescs[$propName] = $propertyDesc;
                }
            }
        }
    }
}
