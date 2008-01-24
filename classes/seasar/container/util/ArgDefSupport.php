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
 * ArgDefの補助クラスです。
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
final class ArgDefSupport {

    /**
     * @var array
     */
    private $argDefs = array();

    /**
     * @var seasar::container::S2Container
     */
    private $container = null;

    /**
     * ArgDefを追加します。
     *
     * @param seasar::container::impl::ArgDef
     */
    public function addArgDef(seasar::container::impl::ArgDef $argDef) {
        if ($this->container != null) {
            $argDef->setContainer($this->container);
        }
        $this->argDefs[] = $argDef;
    }

    /**
     *  ArgDefの数を返します。
     *
     * @return integer
     */
    public function getArgDefSize() {
        return count($this->argDefs);
    }

    /**
     * ArgDefを返します。
     *
     * @param integer
     * @return seasar::container::impl::ArgDef
     */
    public function getArgDef($index) {
        if (!isset($this->argDefs[$index])) {
            throw new OutOfRangeException($index);
        }
        return $this->argDefs[$index];
    }

    /**
     * S2Containerを設定します。
     *
     * @param seasar::container::S2Container $container
     */
    public function setContainer(seasar::container::S2Container $container) {
        $this->container = $container;
        foreach ($this->argDefs as $argDef) {
            $argDef->setContainer($container);
        }
    }
}
