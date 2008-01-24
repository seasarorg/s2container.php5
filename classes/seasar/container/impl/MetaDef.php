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
 * MetaDefの実装クラスです。
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
class MetaDef extends ArgDef {

    /**
     * @var string
     */
    private $name = null;

    /**
     * MetaDef を構築します。
     * 
     * @param string $name
     * @param mixed $value
     */   
    public function __construct($name, $value = null) {
        parent::__construct($value);
        $this->name = $name;
    }

    /**
     * メタデータ定義の名前を返します。
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
