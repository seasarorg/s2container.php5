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
 * プロパティの値に対して検証を行うためのAbstractクラス
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar::container::impl;
abstract class AbstractTypehintPropertyDef extends PropertyDef {

    /**
     * プロパティの値に対して検証を行います。
     * 検証に失敗した場合は例外をスローします。
     *
     * @param mixed $value
     * @throw Exception
     */
    abstract protected function validate($value);

    /**
     * @see seasar::container::impl::ArgDef::getValue()
     */
    public function getValue() {
        $value = parent::getValue();
        $this->validate($value);
        return $value;
    }
}
