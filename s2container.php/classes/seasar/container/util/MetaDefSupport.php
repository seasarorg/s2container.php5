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
 * MetaDefの補助クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
final class MetaDefSupport {

    /**
     * @var array
     */
    private $metaDefs = array();

    /**
     * @var \seasar\container\S2Container
     */
    private $container;

    /**
     * MetaDefSupportを構築します。
     *
     * @param \seasar\container\S2Container $container
     */
    public function __construct(\seasar\container\S2Container $container = null) {
        $this->coontainer_ = $container;
    }

    /**
     * MetaDefを追加します。
     *
     * @param \seasar\container\impl\MetaDef
     */
    public function addMetaDef(\seasar\container\impl\MetaDef $metaDef) {
        if ($this->container != null) {
            $metaDef->setContainer($this->container);
        }
        $this->metaDefs[] = $metaDef;
    }

    /**
     *  MetaDefの数を返します。
     *
     * @return integer
     */
    public function getMetaDefSize() {
        return count($this->metaDefs);
    }

    /**
     * インデックス番号indexで指定されたメタデータ定義を返します。
     * インデックス番号は、追加した順に0, 1, 2…となります。 MetaDefを返します。
     *
     * @param integer
     * @return \seasar\container\impl\MetaDef
     */
    public function getMetaDef($index) {
        if (is_integer($index)) {
            if (!isset($this->metaDefs[$index])) {
                throw new \OutOfRangeException($index);
            }
            return $this->metaDefs[$index];
        }

        foreach ($this->metaDefs as $metaDef) {
            if ($metaDef->getName() === $index) {
                return $metaDef;
            }
        }
        return null;
    }

    /**
     * 指定したメタデータ定義名で登録されているメタデータ定義を取得します。
     * メタデータ定義が登録されていない場合、nullを返します。MetaDefを返します。
     *
     * @param string
     * @return array
     */
    public function getMetaDefs($name) {
        $defs = array();
        foreach ($this->metaDefs as $def) {
            if ($def->getName() === $name) {
                $defs[] = $def;
            }
        }
        return $defs;
    }

    /**
     * S2Containerを設定します。
     *
     * @param \seasar\container\S2Container $container
     */
    public function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
        foreach ($this->metaDefs as $def) {
            $def->setContainer($container);
        }
    }
}
