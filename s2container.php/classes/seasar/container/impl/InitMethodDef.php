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
 * InitMethodDefの実装クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar::container::impl;
class InitMethodDef implements seasar::container::MethodDef {

    /**
     * @var string
     */
    private $methodName = null;

    /**
     * @var seasar::container::util::ArgDefSupport
     */
    private $argDefSupport = null;

    /**
     * @var seasar::container::S2Container
     */
    private $container = null;

    /**
     * @var string
     */
    private $expression = null;

    /**
     * InitMethodDefを構築します。
     *
     * @param stirng method name
     */
    public function __construct($methodName = null) {
        $this->argDefSupport = new Seasar::Container::Util::ArgDefSupport();
        $this->methodName = $methodName;
    }

    /**
     * メソッド名を返します。
     *
     * @return string
     */
    public function getMethodName() {
        return $this->methodName;
    }

    /**
     * 引数定義を追加します。
     *
     * @param seasar::container::impl::ArgDef $argDef
     */
    public function addArgDef(ArgDef $argDef) {
        $this->argDefSupport->addArgDef($argDef);
    }

    /**
     * 登録されている引数定義の数を返します。
     *
     * @see seasar::container::util::ArgDefAware::getArgDefSize()
     * @return integer
     */
    public function getArgDefSize() {
        return $this->argDefSupport->getArgDefSize();
    }

    /**
     * 指定されたインデックス番号indexの引数定義を返します。
     * インデックス番号は、 登録した順番に 0,1,2,… となります。
     *
     * @see seasar::container::util::ArgDefAware::getArgDef()
     * @param integer $index
     * @return seasar::container::impl::ArgDef
     */
    public function getArgDef($index) {
        return $this->argDefSupport->getArgDef($index);
    }

    /**
     * 引数および式を評価するコンテキストとなるS2コンテナを返します。
     *
     * @return seasar::container::S2Container
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * 引数および式を評価するコンテキストとなるS2コンテナを設定します。
     *
     * @param seasar::container::S2Container $container
     */
    public function setContainer(seasar::container::S2Container $container) {
        $this->container = $container;
        $this->argDefSupport->setContainer($container);
    }

    /**
     * 実行される式を返します。
     *
     * @return string
     */
    public function getExpression() {
        return $this->expression;
    }

    /**
     * 実行される式を設定します。
     *
     * @param string $expression
     */
    public function setExpression($expression) {
        $this->expression = $expression;
    }
}
