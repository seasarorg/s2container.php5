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
 * PropertyDefの補助クラスです。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
final class PropertyDefSupport {

    /**
     * @var array
     */
    private $propertyDefs    = array();

    /**
     * @var array
     */
    private $propertyDefList = array();

    /**
     * @var \seasar\container\S2Container
     */
    private $container       = null;

    /**
     * PropertyDefを追加します。
     *
     * @param \seasar\container\impl\PropertyDef
     */
    public function addPropertyDef(\seasar\container\impl\PropertyDef $propertyDef) {
        if ($this->container != null) {
            $propertyDef->setContainer($this->container);
        }
        $this->propertyDefs[$propertyDef->getPropertyName()] = $propertyDef;
        $this->propertyDefList[] = $propertyDef->getPropertyName();
    }

    /**
     * すべてのPropertyDefを返します。
     *
     * @return array
     */
    public function getPropertyDefs() {
        return $this->propertyDefs;
    }

    /**
     *  PropertyDefの数を返します。
     *
     * @return integer
     */
    public function getPropertyDefSize() {
        return count($this->propertyDefs);
    }

    /**
     * PropertyDefを返します。
     *
     * @param string|integer $value
     * @return \seasar\container\impl\PropertyDef
     */
    public function getPropertyDef($value) {
        if (is_integer($value)) {
            if (isset($this->propertyDefList[$value])) {
                return $this->propertyDefs[$this->propertyDefList[$value]];
            } else {
                throw new \OutOfRangeException($value);
            }
        }
        if (!$this->hasPropertyDef($value)) {
            throw new \OutOfRangeException($value);
        }
        return $this->propertyDefs[$value];
    }

    /**
     * PropertyDefを持っているかどうか返します。
     *
     * @param string $propertyName
     * @return boolean
     */
    public function hasPropertyDef($propertyName) {
        return array_key_exists($propertyName, $this->propertyDefs);
    }

    /**
     * S2Containerを設定します。
     *
     * @param \seasar\container\S2Container $container
     */
    public function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
        foreach ($this->propertyDefs as $name => $def) {
            $def->setContainer($container);
        }
    }
}
