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
 * コンポーネントのコンストラクタおよびメソッドに与えられる引数定義のためのクラスです。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class ArgDef {

    /**
     * @var mixed
     */
    private $value      = null;

    /**
     * @var \seasar\container\S2Container
     */
    private $container  = null;

    /**
     * @var string
     */
    private $expression = null;

    /**
     * @var \seasar\container\ComponentDef
     */
    private $childComponentDef = null;

    /**
     * ArgDefを構築します。
     *
     * @param mixed $value
     */
    public function __construct($value = null) {
        $this->value= $value;
    }

    /**
     * 引数定義の値を返します。
     *
     * @return mixed
     */
    public final function getValue() {
        if ($this->expression !== null) {
            return \seasar\util\EvalUtil::execute(\seasar\util\EvalUtil::formatExpression($this->expression));
        }
        if ($this->childComponentDef instanceof \seasar\container\ComponentDef) {
            return $this->childComponentDef->getComponent();
        }
        return $this->value;
    }

    /**
     * 引数定義の値を設定します。
     *
     * @param mixed $value
     */
    public final function setValue($value) {
        $this->value= $value;
    }

    /**
     * 引数を評価するコンテキストとなるS2コンテナを返します。
     *
     * @return \seasar\container\S2Container
     */
    public final function getContainer() {
        return $this->container;
    }

    /**
     * 引数を評価するコンテキストとなるS2コンテナを設定します。
     *
     * @param \seasar\container\S2Container $container
     */
    public final function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
        if ($this->childComponentDef instanceof ComponentDef) {
            $this->childComponentDef->setContainer($container);
        }
    }

    /**
     * 引数定義の値となる式を返します。
     *
     * @return string
     */
    public final function getExpression() {
        return $this->expression;
    }

    /**
     * 引数定義の値となる式を設定します。
     *
     * @param string $expression
     */
    public final function setExpression($expression) {
        $this->expression = $expression;
    }

    /**
     * 引数定義の値となるコンポーネント定義を設定します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     */
    public final function setChildComponentDef(\seasar\container\ComponentDef $componentDef) {
        if ($this->container != null) {
            $componentDef->setContainer($this->container);
        }
        $this->childComponentDef= $componentDef;
    }

    /**
     * 引数定義の値となるコンポーネント定義を返します。
     *
     * @return \seasar\container\ComponentDef
     */
    public final function getChildComponentDef() {
         return $this->childComponentDef;
    }
}
