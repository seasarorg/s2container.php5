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
 * AspectDefの補助クラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
final class AspectDefSupport {

    /**
     * @var array
     */
    private $aspectDefs = array();

    /**
     * @var \seasar\container\S2Container
     */
    private $container;

    /**
     * AspectDefを追加します。
     *
     * @param \seasar\container\impl\AspectDef
     */
    public function addAspectDef(\seasar\container\impl\AspectDef $aspectDef) {
        if ($this->container !== null) {
            $aspectDef->setContainer($this->container);
        }
        $this->aspectDefs[] = $aspectDef;
    }

    /**
     * AspectDefの数を返します。
     *
     * @return integer
     */
    public function getAspectDefSize() {
        return count($this->aspectDefs);
    }

    /**
     * すべてのAspectDefを返します。
     *
     * @return array
     */
    public function getAspectDefs() {
        return $this->aspectDefs;
    }

    /**
     * AspectDefを返します。
     *
     * @param integer
     * @return \seasar\container\impl\AspectDef
     */
    public function getAspectDef($index) {
        if (!isset($this->aspectDefs[$index])) {
            throw new \OutOfRangeException($index);
        }
        return $this->aspectDefs[$index];
    }

    /**
     * S2Containerを設定します。
     *
     * @param \seasar\container\S2Container $container
     */
    public function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
        foreach ($this->aspectDefs as $def) {
            $def->setContainer($container);
        }
    }
}
