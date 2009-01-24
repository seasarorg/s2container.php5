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
 * singleton用のInstanceDefです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.deployer
 * @author    klove
 */
namespace seasar\container\deployer;
class InstanceSingletonDef implements \seasar\container\InstanceDef {
    /**
     * @var string \seasar\container\InstanceDef::SINGLETON_NAME
     */
    private $name;

    /**
     * InstanceSingletonDef を構築します。
     *
     * @param string $name \seasar\container\InstanceDef::SINGLETON_NAME
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * インスタンス定義の文字列表現を返します。
     *
     * @return string \seasar\container\InstanceDef::SINGLETON_NAME
     */
    public function getName() {
        return $this->name;
    }

    /**
     * インスタンス定義に基づいた、コンポーネント定義componentDefのComponentDeployerを返します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @return object
     */
    public function createComponentDeployer(\seasar\container\ComponentDef $componentDef) {
        return new SingletonComponentDeployer($componentDef);
    }
}
