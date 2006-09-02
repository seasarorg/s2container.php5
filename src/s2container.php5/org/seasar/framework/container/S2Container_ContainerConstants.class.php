<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * S2コンテナで使用される定数を定義するインターフェースです。
 * <p>
 * セパレータ文字や定義済みコンポーネントキー(コンポーネント名)などの定数を定義しています。
 * </p>
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_ContainerConstants
{
    /**
     * コンポーネントのインスタンスモード　<b>singleton</b>　を表す定数です。
     */
    const INSTANCE_SINGLETON = "singleton";

    /**
     * コンポーネントのインスタンスモード　<b>prototype</b>　を表す定数です。
     */
    const INSTANCE_PROTOTYPE = "prototype";

    /**
     * コンポーネントのインスタンスモード　<b>request</b>　を表す定数です。
     */
    const INSTANCE_REQUEST = "request";

    /**
     * コンポーネントのインスタンスモード　<b>session</b>　を表す定数です。
     */
    const INSTANCE_SESSION = "session";

    /**
     * コンポーネントのインスタンスモード　<b>outer</b>　を表す定数です。
     */
    const INSTANCE_OUTER = "outer";

    /**
     * コンポーネントの自動バインディングモード　<b>auto</b>　を表す定数です。
     */
    const AUTO_BINDING_AUTO = "auto";

    /**
     * コンポーネントの自動バインディングモード　<b>constructor</b>　を表す定数です。
     */
    const AUTO_BINDING_CONSTRUCTOR = "constructor";

    /**
     * コンポーネントの自動バインディングモード　<b>property</b>　を表す定数です。
     */
    const AUTO_BINDING_PROPERTY = "property";

    /**
     * コンポーネントの自動バインディングモード　<b>none</b>　を表す定数です。
     */
    const AUTO_BINDING_NONE = "none";

    /**
     * 名前空間とコンポーネント名の区切り(char)を表す定数です。
     * preg_match(/"(.+)". 
     *   S2Container_ContainerConstants::NS_SEP ."(.+)"/);
     */
    const NS_SEP = '\.';

    /**
     * S2コンテナのコンポーネントキーを表す定数です。
     * 
     * @see S2ContainerImpl
     */
    const CONTAINER_NAME = "container";

    /**
     * {@link S2Container_ComponentDef コンポーネント定義}を配列に保持する場合などに、
     * キーとして使用する定数です。
     */
    const COMPONENT_DEF_NAME = "componentDef";

    /**
     * {@link S2Container_S2MethodInvocation}に渡すパラメータ(Proxyオブジェクト)のキーとして使用する定数です。
     */
    const S2AOP_PROXY_NAME = "aopProxy";
}
?>
