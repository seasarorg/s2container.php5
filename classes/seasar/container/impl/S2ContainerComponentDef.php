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
 * S2Container用の ComponentDefです。
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
class S2ContainerComponentDef extends SimpleComponentDef {

    /**
     * S2ContainerComponentDefを構築します。
     *
     * @param seasar::container::S2Container $container
     * @param string $name
     */
    public function __construct(seasar::container::S2Container $container, $name) {
        parent::__construct($container, $name);
    }

    /**
     * このコンポーネント定義を含むS2コンテナを返します。
     *
     * @return seasar::container::S2Container
     */
    public function getContainer() {
        return parent::getComponent();
    }

    /**
     * 定義に基づいてコンポーネントを返します。
     *
     * @see seasar::container::ComponentDef::getComponent()
     * @return seasar::container::S2Container
     */
    public function getComponent() {
        return $this->getContainer();
    }
}
