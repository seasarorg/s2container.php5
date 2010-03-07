<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 * DIとAOPをサポートしたS2コンテナのインターフェースです。
 * 
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
interface S2Container {
    /**
     * 指定されたキーに対応するコンポーネントを返します。
     * <p>
     * キーが文字列の場合、名前が一致するコンポーネントを返します。
     * キーがクラスまたはインターフェースの場合、キーの型に代入可能なコンポーネントを返します。
     * </p>
     * 
     * @param string $componentKey コンポーネントを取得するためのキー
     * @throws ComponentNotFoundRuntimeException コンポーネントが見つからない場合
     * @throws TooManyRegistrationRuntimeException
     *         同じ名前、または同じクラスに複数のコンポーネントが登録されている場合
     * @throws CyclicReferenceRuntimeException
     *         コンストラクタ・インジェクションでコンポーネントの参照が循環している場合
     * @return mixed コンポーネント
     */
    public function getComponent($componentKey);

    /**
     * 指定されたキーに対応する複数のコンポーネントを検索して返します。
     * <p>
     * 検索の範囲は現在のS2コンテナおよび、インクルードしているS2コンテナの階層全体です。
     * キーに対応するコンポーネントが最初に見つかったS2コンテナを対象とします。
     * このS2コンテナから，キーに対応する全てのコンポーネントを配列で返します。
     * 返される配列に含まれるコンポーネントは全て同一のS2コンテナに登録されたものです。
     * </p>
     * 
     * @param string $componentKey コンポーネントを取得するためのキー
     * @return array キーに対応するコンポーネントの配列を返します。 キーに対応するコンポーネントが存在しない場合は長さ0の配列を返します。
     * @throws CyclicReferenceRuntimeException
     *         コンストラクタ・インジェクションでコンポーネントの参照が循環している場合
     */
    public function findComponents($componentKey);

    /**
     * コンポーネントを登録します。
     * 
     * <p>
     * S2コンテナに無名のコンポーネントとして登録します。 登録されたコンポーネントはインジェクションやアスペクトの適用などは出来ません。
     * 他のコンポーネント構築時に依存オブジェクトとして利用することが可能です。
     * </p>
     * 
     * @param object|string|ComponentDef $component コンポーネント
     * @param string $componentName コンポーネント名
     */
    public function register($component, $componentName = "");

    /**
     * コンテナに登録されているコンポーネント定義の数を返します。
     * @return int コンポーネント定義の数
     */
    public function getComponentDefSize();

    /**
     * コンテナに登録されているコンポーネント定義の名前を返します。
     * @return array コンポーネント定義の名前
     */
    public function getComponentDefNames();

    /**
     * 番号で指定された位置のコンポーネント定義を返します。
     * 
     * @param int|string $index キー
     * @return ComponentDef コンポーネント定義
     * @throws ComponentNotFoundRuntimeException コンポーネント定義が見つからない場合
     */
    public function getComponentDef($index);

    /**
     * 指定されたキーに対応する複数のコンポーネント定義を検索して返します。
     * <p>
     * 検索の範囲は現在のS2コンテナおよび、インクルードしているS2コンテナの階層全体です。
     * キーに対応するコンポーネントが最初に見つかったS2コンテナを対象とします。
     * このS2コンテナから，キーに対応する全てのコンポーネント定義を配列で返します。
     * 返される配列に含まれるコンポーネント定義は全て同一のS2コンテナに登録されたものです。
     * </p>
     * 
     * @param string $componentKey コンポーネント定義を取得するためのキー
     * @return array キーに対応するコンポーネント定義の配列を返します。 キーに対応するコンポーネント定義が存在しない場合は長さ0の配列を返します。
     */
    public function findComponentDefs($componentKey);

    /**
     * 指定されたキーに対応するコンポーネント定義が存在する場合<b>true</b>を返します。
     * 
     * @param string $componentKey キー
     * @return boolean キーに対応するコンポーネント定義が存在する場合<b>true</b>、そうでない場合は<b>false</b>
     */
    public function hasComponentDef($componentKey);
}

