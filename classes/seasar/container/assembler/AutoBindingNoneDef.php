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
 * 自動バンディング定義の何も行わない版です。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class AutoBindingNoneDef implements \seasar\container\AutoBindingDef {

    /**
     * 自動バインディング定義名
     * \seasar\container\AutoBindingDef::AUTO_NAME
     *
     * @var string 
     */
    private $name = null;

    /**
     * AutoBindingNoneoDefを作成します。
     *
     * @param string $name \seasar\container\AutoBindingDef::NONE_NAME
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @see \seasar\container\AutoBindingDef::getName()
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @see \seasar\container\AutoBindingDef::createConstructorAssembler()
     */
    public function createConstructorAssembler(\seasar\container\ComponentDef $componentDef) {
        return new ManualConstructorAssembler($componentDef);
    }

    /**
     * @see \seasar\container\AutoBindingDef::createPropertyAssembler()
     */
    public function createPropertyAssembler(\seasar\container\ComponentDef $componentDef) {
        return new ManualPropertyAssembler($componentDef);
    }
}
