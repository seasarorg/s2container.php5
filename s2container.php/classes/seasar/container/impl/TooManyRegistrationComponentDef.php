<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * 1つのキーに複数のコンポーネントが登録されるときの ComponentDefです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class TooManyRegistrationComponentDef extends SimpleComponentDef {

    /**
     * @var string
     */
    private $key = null;

    /**
     * @var array
     */
    private $componentDefs = array();

    /**
     * TooManyRegistrationComponentDefを構築します。
     *
     * @param string $key コンポーネントキー
     */
    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * コンポーネントのキーを返します。
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * 同じキーで登録されたコンポーネント定義を追加します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     */
    public function addComponentDef(\seasar\container\ComponentDef $componentDef) {
        $this->componentDefs[] = $componentDef;
    }

    /**
     * ComponentDefの数を返します。
     *
     * @return int
     */
    public function getComponentDefSize() {
        return count($this->componentDefs);
    }
    
    /**
     * @see \seasar\container\ComponentDef::getComponent()
     */
    public function getComponent() {
        throw new \seasar\container\exception\TooManyRegistrationRuntimeException($this->key, $this->getComponentClasses());
    }

    /**
     * 複数登録されたコンポーネント定義の配列を返します。
     *
     * @return array 
     */
    public function getComponentDefs() {
        return $this->componentDefs;
    }

    /**
     * 複数登録されたコンポーネントの定義上のクラスの配列を返します。
     *
     * @return array
     */
    public function getComponentClasses() {
        $classes = array();
        foreach ($this->componentDefs as $componentDef) {
            $classes[] = $componentDef->getComponentClass();
        }
        return $classes;
    }
}
