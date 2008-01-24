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
 * InitMethodDefの補助クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar::container::util;
class InitMethodDefSupport {

    /**
     * @var array
     */
    private $methodDefs = array();

    /**
     * @var seasar::container::S2Container
     */
    private $container  = null;

    /**
     * InitMethodDefを追加します。
     *
     * @param seasar::container::impl::InitMethodDef $methodDef
     */
    public function addInitMethodDef(seasar::container::impl::InitMethodDef $methodDef) {
        if ($this->container !== null) {
            $methodDef->setContainer($this->container);
        }
        $this->methodDefs[] = $methodDef;
    }

    /**
     *  InitMethodDefの数を返します。
     *
     * @return integer
     */
    public function getInitMethodDefSize() {
        return count($this->methodDefs);
    }

    /**
     * InitMethodDefを返します。
     *
     * @param integer
     * @return seasar::container::impl::InitMethodDef
     */
    public function getInitMethodDef($index) {
        return $this->methodDefs[$index];
    }

    /**
     * S2Containerを設定します。
     *
     * @param seasar::container::S2Container $container
     */
    public function setContainer(seasar::container::S2Container $container) {
        $this->container = $container;
        $o = $this->getInitMethodDefSize();
        for ($i = 0; $i < $o; $i++) {
            $this->getInitMethodDef($i)->setContainer($container);
        }
    }
}
