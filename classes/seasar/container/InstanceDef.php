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
 * コンポーネントのインスタンスをS2コンテナ上でどのように管理するのかを定義します。
 *
 * インスタンス定義の種類には、以下のものがあります。
 *   singleton(default) S2コンテナ上で唯一のインスタンスになります。
 *   prototype コンポーネントが必要とされる度に異なるインスタンスになります。
 * それぞれ、 インスタンスが生成されるタイミングは、そのコンポーネントが必要とされる時になります。 
 * また、 その時点で存在する「コンテキスト」に属するコンポーネントのみインジェクションが可能です。
 *
 * インスタンス定義の指定方法には、以下のものがあります。
 * diconファイル  <component>のinstance属性で指定します。
 * アノテーション @S2Componentのinstance値で指定します。
 *
 * インスタンス定義を省略した場合はsingletonを指定したことになります。
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
interface InstanceDef {
    /**
     * インスタンス定義「singleton」を表す定数です。
     */
    const SINGLETON_NAME = 'singleton';

    /**
     * インスタンス定義「prototype」を表す定数です。
     */
    const PROTOTYPE_NAME = 'prototype';

    /**
     * インスタンス定義の文字列表現を返します。
     *
     * @return string
     */
    public function getName();

    /**
     * インスタンス定義に基づいた、コンポーネント定義componentDefのComponentDeployerを返します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @return \seasar\container\deployer\ComponentDeployer
     */
    public function createComponentDeployer(\seasar\container\ComponentDef $componentDef);
}
