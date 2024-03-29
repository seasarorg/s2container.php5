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
 * 自動バインディングを適用する範囲を表す自動バインディング定義のインターフェースです。
 *
 * 自動バインディング定義には、 以下のものがあります。
 *  auto コンストラクタとプロパティの両方で、 自動バインディングを適用します。
 *  none すべての自動バインディングを適用しません。
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
interface AutoBindingDef {

    /**
     * 自動バインディング定義名「auto」を表す定数です。
     */
    const AUTO_NAME = 'auto';

   /**
    * 自動バインディング定義名「none」を表す定数です。
    */
    const NONE_NAME = 'none';

    /**
     * 自動バインディング定義名を返します。
     *
     * @return string
     */
    public function getName();

    /**
     * 自動バインディング定義に基づき、 componentDefに対するConstructorAssemblerを返します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @return \seasar\container\assembler\ConstructorAssembler
     */
    public function createConstructorAssembler(\seasar\container\ComponentDef $componentDef);

    /**
     * 自動バインディング定義に基づき、 componentDefに対するPropertyAssemblerを返します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @return \seasar\container\assembler\PropertyAssembler
     */
    public function createPropertyAssembler(\seasar\container\ComponentDef $componentDef);
}
