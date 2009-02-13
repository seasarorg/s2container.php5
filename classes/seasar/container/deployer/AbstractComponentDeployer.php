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
 * ComponentDeployerの抽象クラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.deployer
 * @author    klove
 */
namespace seasar\container\deployer;
abstract class AbstractComponentDeployer {

    /**
     * @var \seasar\container\ComponentDef
     */
    private $componentDef         = null;

    /**
     * @var \seasar\container\assembler\AbstractAssembler
     */
    private $constructorAssembler = null;

    /**
     * @var \seasar\container\assembler\AbstractAssembler
     */
    private $propertyAssembler    = null;

    /**
     * @var \seasar\container\assembler\AbstractAssembler
     */
    private $initMethodAssembler  = null;

    /**
     * ComponentDefployer を構築します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        $this->componentDef = $componentDef;
        $this->setupAssembler();
    }

    /**
     * インスタンス定義に応じてインスタンス生成や外部コンテキストへの配備などを行った後に、
     * そのコンポーネントのインスタンスを返します。
     *
     * @return object
     */
    abstract public function deploy();

    /**
     * ComponentDefを返します。
     *
     * @return \seasar\container\ComponentDef
     */
    protected final function getComponentDef() {
        return $this->componentDef;
    }

    /**
     * ConstructorAssemblerを返します。
     *
     * @return \seasar\container\assembler\AbstractAssembler
     */
    protected final function getConstructorAssembler() {
        return $this->constructorAssembler;
    }

    /**
     * PropertyAssemblerを返します。
     *
     * @return \seasar\container\assembler\AbstractAssembler
     */
    protected final function getPropertyAssembler() {
        return $this->propertyAssembler;
    }

    /**
     * MethodAssemblerを返します。
     *
     * @return \seasar\container\assembler\AbstractAssembler
     */
    protected final function getInitMethodAssembler() {
        return $this->initMethodAssembler;
    }

    /**
     * アセンブラを準備します。
     */
    protected function setupAssembler() {
        $this->constructorAssembler = $this->componentDef->getAutoBindingDef()->createConstructorAssembler($this->componentDef);
        $this->propertyAssembler    = $this->componentDef->getAutoBindingDef()->createPropertyAssembler($this->componentDef);
        $this->initMethodAssembler  = new \seasar\container\assembler\InitMethodAssembler($this->componentDef);
    }
}
