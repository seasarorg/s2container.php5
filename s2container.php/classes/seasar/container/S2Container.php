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
 * DIとAOPをサポートしたS2コンテナのインターフェースです。
 * 
 * <p>
 * S2Containerの役割について <br>
 * コンポーネントの管理を行う機能を提供します。 コンポーネントとは１つかまたそれ以上のクラスで構成されるPHPオブジェクトです。
 * S2コンテナはコンポーネントの生成、コンポーネントの初期化、コンポーネントの取得を提供します。
 * コンポーネントを取得するキーには、コンポーネント名、コンポーネントのクラス、またはコンポーネントが実装するインターフェースを指定することができます。
 * </p>
 * <br>
 * 
 * <p>
 * S2コンテナのインスタンス階層について<br>
 * S2コンテナ全体は複数のコンテナにより階層化されています。 一つのコンテナは複数のコンテナをインクルードすることができます。
 * 複数のコンテナが同一のコンテナをインクルードすることができます。
 * <ul>
 * <li>{@link http://s2container.seasar.org/ja/images/include_range_20040706.png インクルードの参照範囲についてのイメージ}</li>
 * <li>{@link http://s2container.seasar.org/ja/images/include_search_20040706.png コンテナの検索順についてのイメージ}</li>
 * </ul>
 * </p>
 * <br>
 * 
 * <p>
 * S2コンテナのインジェクションの種類について<br>
 * S2コンテナは3種類のインジェクションをサポートします。
 * <ul>
 * <li>{@link ConstructorAssembler コンストラクタ・インジェクション}<br>
 *     コンストラクタ引数を利用してコンポーネントをセットします。 </li>
 * <li>{@link PropertyAssembler セッター・インジェクション}<br>
 *     セッターメソッドを利用してコンポーネントをセットします。 </li>
 * <li>{@link MethodAssembler メソッド・インジェクション}<br>
 *     任意のメソッドを利用してコンポーネントをセットします。 </li>
 * </ul>
 * </p>
 * <br>
 * 
 * <p>
 * S2Containerが持つメソッドの分類について<br>
 * コンテナへの登録、コンテナからのコンポーネント取得、検索などを行うコンポーネントを管理する機能
 * <ul>
 * <li>{@link S2Container::getComponent() getComponent}</li>
 * <li>{@link S2Container::getComponentDefSize() getComponentDefSize}</li>
 * <li>{@link S2Container::getComponentDef() getComponentDef}</li>
 * <li>{@link S2Container::findComponents() findComponents}</li>
 * <li>{@link S2Container::findComponentDefs() findComponentDefs}</li>
 * <li>{@link S2Container::hasComponentDef() hasComponentDef}</li>
 * <li>{@link S2Container::register() register}</li>
 * <li>{@link S2Container::injectDependency() injectDependency}</li>
 * </ul>
 * <br>
 * 
 * コンテナの初期化、終了処理、コンテナの階層化、階層化されたコンテナへのアクセスなどコンテナを管理する機能
 * <ul>
 * <li>{@link S2Container::getNamespace() getNamespace}</li>
 * <li>{@link S2Container::setNamespace() setNamespace}</li>
 * <li>{@link S2Container::getPath() getPath}</li>
 * <li>{@link S2Container::setPath() setPath}</li>
 * <li>{@link S2Container::init() init}</li>
 * <li>{@link S2Container::destroy() destroy}</li>
 * <li>{@link S2Container::hasDescendant() hasDescendant}</li>
 * <li>{@link S2Container::getDescendant() getDescendant}</li>
 * <li>{@link S2Container::registerDescendant() registerDescendant}</li>
 * <li>{@link S2Container::includeChild() includeChild}</li>
 * <li>{@link S2Container::getChildSize() getChildSize}</li>
 * <li>{@link S2Container::getChild() getChild}</li>
 * <li>{@link S2Container::getRoot() getRoot}</li>
 * <li>{@link S2Container::setRoot() setRoot}</li>
 * </ul>
 * </p>
 * 
 * @copyright 2005-2008 the Seasar Foundation and the Others.
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
    
    /**
     * <b>path</b>を読み込んだS2コンテナが存在する場合<b>true</b>を返します。
     * 
     * @param string $path パス
     * @return boolean <b>path</b>を読み込んだS2コンテナが存在する場合<b>true</b>、そうでない場合は<b>false</b>
     */
    public function hasDescendant($path);

    /**
     * <b>path</b>を読み込んだS2コンテナを返します。
     * 
     * @param string $path パス
     * @return S2Container S2コンテナ
     * @throws ContainerNotRegisteredRuntimeException S2コンテナが見つからない場合
     */    
    public function getDescendant($path);
    
    /**
     * <b>descendant</b>を子孫コンテナとして登録します。
     * <p>
     * 子孫コンテナとは、このコンテナに属する子のコンテナや、その子であるコンテナです。
     * </p>
     * 
     * @param S2Container $descendant 子孫コンテナ
     */
    public function registerDescendant(S2Container $descendant);

    /**
     * コンテナを子としてインクルードします。
     * 
     * @param S2Container $child インクルードするS2コンテナ
     */
    public function includeChild(S2Container $child);
    
    /**
     * インクルードしている子コンテナの数を返します。
     * 
     * @return int 子コンテナの数
     */
    public function getChildSize();

    /**
     * 番号で指定された位置の子コンテナを返します。
     * 
     * @param int $index 子コンテナの番号
     * @return S2Container 子コンテナ
     */
    public function getChild($index);

    /**
     * 名前空間を返します。
     * 
     * @return string 名前空間
     */    
    public function getNamespace();

    /**
     * 名前空間を設定します。
     * 
     * @param string namespace 名前空間
     */    
    public function setNamespace($namespace);

    /**
     * 設定ファイルの<b>path</b>を返します。
     * 
     * @return string 設定ファイルの<b>path</b>
     */    
    public function getPath();

    /**
     * 設定ファイルの<b>path</b>を設定します。
     * 
     * @param string $path
     *            設定ファイルの<b>path</b>
     */    
    public function setPath($path);

    /**
     * ルートのS2コンテナを返します。
     * 
     * @return S2Container ルートのS2コンテナ
     */
    public function getRoot();

    /**
     * ルートのS2コンテナを設定します。
     * 
     * @param S2Container $root S2コンテナ
     */    
    public function setRoot(S2Container $root);
}

