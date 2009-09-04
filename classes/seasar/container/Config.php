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
 * \seasar\containerの設定ファイルです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
abstract class Config {

    /**
     * S2コンテナのコンポーネントキーを表す定数です。
     * 
     * @see \seasar\container\S2Container
     */
    const CONTAINER_NAME = "container";

    /**
     * コンポーネント定義を表す定数です。
     * 
     * @see \seasar\container\ComponentDef
     */
    const COMPONENT_DEF_NAME = "componentDef";

    /**
     * S2Container::getComponent()でコンポーネントが見つからなかった場合に、コンポーネントキーがクラスを表す場合は、
     * 自動でコンポーネントを登録するかどうかを指定します。
     *
     * @boolean
     */
    public static $AUTO_REGISTER_WHEN_NOT_FOUND = true;

}

/**
 * S2RuntimeExceptionへの例外メッセージの登録
 */
\seasar\exception\S2RuntimeException::$MESSAGES[105] = '"autobinding def name [$args[0]] not found."';
\seasar\exception\S2RuntimeException::$MESSAGES[106] = '"instance def name [$args[0]] not found."';
\seasar\exception\S2RuntimeException::$MESSAGES[107] = '"the circulation reference was occurred in [$args[0]]"';
\seasar\exception\S2RuntimeException::$MESSAGES[109] = '"component [$args[0]] not found."';
\seasar\exception\S2RuntimeException::$MESSAGES[112] = '"the circulation instantiation was occurred in [$args[0]]."';
\seasar\exception\S2RuntimeException::$MESSAGES[113] = '"property [$args[1]] not found in [$args[0]]."';
\seasar\exception\S2RuntimeException::$MESSAGES[114] = '"The circulation include was occurred in $args[0], pathway $args[1]."';
\seasar\exception\S2RuntimeException::$MESSAGES[115] = '"Two or more components[$args[1]] are registered as $args[0]."';
\seasar\exception\S2RuntimeException::$MESSAGES[116] = '"Actual class [$args[1]] is not applicable in defined class [$args[0]]"';
