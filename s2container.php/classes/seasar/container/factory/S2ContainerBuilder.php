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
 * 特定の形式の定義情報からS2コンテナを組み立てるビルダのインターフェースです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar\container\factory;
interface S2ContainerBuilder {

    /**
     * 指定された設定ファイルからS2コンテナを組み立てます。
     *
     * @param string dicon path
     */
    public function build($path);

    /**
     * 指定された設定ファイルからS2コンテナを組み立て、親S2コンテナに対してインクルードします。
     *
     * @param S2Container
     * @param string dicon path
     */   
    public function includeChild(\seasar\container\S2Container $parent, $path);
}
