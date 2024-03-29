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
 * S2コンテナが管理するコンポーネントの定義を表すインタフェースです。
 * <p>
 * コンポーネント定義は、 コンポーネントの管理に必要な以下の情報を保持します。
 * <ul>
 * <li>ライフサイクル<br>
 *     コンポーネントのスコープや、生成と消滅については、 このコンポーネントの{@link http://s2container.php5.seasar.org/DIContainer.html#InstanceDef インスタンス定義}で設定します。
 *     生成については、 {@link http://s2container.php5.seasar.org/php.html#compoonent コンポーネント生成のPHP式}により指定することも可能です。</li>
 * <li>依存性注入(Dependency Injection)<br>
 *     このコンポーネントが依存する他のコンポーネントやパラメータは、 {@link ArgDef コンストラクタ引数定義}、
 * {@link InitMethodDef 初期化メソッド定義}、 {@link PropertyDef プロパティ定義}などにより設定します。</li>
 * <li>アスペクト<br>
 *     このコンポーネントの{@link AspectDef アスペクト定義}により設定します。</li>
 * <li>メタデータ<br>
 *     {@link MetaDef メタデータ定義}により、 コンポーネントに付加情報を設定できます。 メタデータは、
 * 特殊なコンポーネントであることを識別する場合などに利用します。</li>
 * </ul>
 * </p>
 * 
 * @see ArgDef
 * @see PropertyDef
 * @see InitMethodDef
 * @see DestroyMethodDef
 * @see AspectDef
 * @see MetaDef
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
interface ComponentDef {
    /**
     * 定義に基づいてコンポーネントを返します。
     * 
     * @return object コンポーネント
     * @throws TooManyRegistrationRuntimeException
     *             コンポーネント定義が重複している場合
     * @throws CyclicReferenceRuntimeException
     *             コンポーネント間に循環参照がある場合
     * 
     * @see TooManyRegistrationComponentDef
     */
    public function getComponent();

    /**
     * このコンポーネント定義を含むS2コンテナを返します。
     * 
     * @return S2Container S2コンテナ
     */
    public function getContainer();

    /**
     * このコンポーネント定義を含むS2コンテナを設定します。
     * 
     * @param S2Container $container S2コンテナ
     */
    public function setContainer(\seasar\container\S2Container $container);

    /**
     * 定義上のクラスを返します。 diconファイルの<b><component/></b>タグにおける、
     * <b>class</b>属性で指定されたクラスを表します。 自動バインディングされる際には、
     * このクラス(インターフェース)が使用されます。
     * 
     * @return \ReflectionClass 定義上のクラス
     */
    public function getComponentClass();

    /**
     * コンポーネント名を返します。
     * 
     * @return string コンポーネント名
     */
    public function getComponentName();

    /**
     * コンポーネント名を設定します。
     * 
     * @param string $name コンポーネント名
     */
    public function setComponentName($name);

    /**
     * 自動バインディングモードを返します。
     * 
     * @return AutoBindingDef 自動バインディングモード
     */
    public function getAutoBindingDef();

    /**
     * 自動バインディングモードを設定します。
     * 
     * @param AutoBindingDef $def 自動バインディングモード
     */
    public function setAutoBindingDef(\seasar\container\AutoBindingDef $def);

    /**
     * インスタンスモードを返します。
     * 
     * @return InstanceDef インスタンスモード
     */
    public function getInstanceDef();

    /**
     * インスタンスモードを設定します。
     * 
     * @param InstanceDef $def インスタンスモード
     */
    public function setInstanceDef(\seasar\container\InstanceDef $def);

    /**
     * すべてのArgDefを返します。
     *
     * @return array
     */
    public function getArgDefs();

    /**
     * インデックスで指定されたArgDefを返します。
     *
     * @param integer $index
     * @return \seasar\container\impl\ArgDef
     */
    public function getArgDef($index);

    /**
     * ArgDefの数を返します。
     *
     * @return integer
     */
    public function getArgDefSize();

    /**
     * ArgDefを追加します。
     *
     * @param \seasar\container\impl\ArgDef $argDef
     */
    public function addArgDef(\seasar\container\impl\ArgDef $argDef);

    /**
     * すべてのProperetyDefを返します。
     *
     * @return array
     */
    public function getPropertyDefs();

    /**
     * インデックスで指定されたPropertyDefを返します。
     *
     * @param integer $index
     * @return \seasar\container\impl\PropertyDef
     */
    public function getPropertyDef($index);

    /**
     * PropertyDefを保持しているかどうかを返します。
     *
     * @param string $propertyName
     * @return boolean
     */
    public function hasPropertyDef($propertyName);

    /**
     * PropertyDefを追加します。
     *
     * @param \seasar\container\impl\PropertyDef $propertyDef
     */
    public function addPropertyDef(\seasar\container\impl\PropertyDef $propertyDef);

    /**
     * PropertyDef数を返します。
     *
     * @return integer
     */
    public function getPropertyDefSize();

    /**
     * すべての初期化メソッド定義をを返します。
     *
     * @return array
     */
    public function getInitMethodDefs();

    /**
     * インデックスで指定されたInitMethodDefを返します。
     *
     * @param integer $index
     * @return \seasar\container\impl\InitMethodDef
     */
    public function getInitMethodDef($index);

    /**
     * InitMethodDefの数を返します。
     *
     * @return integer
     */
    public function getInitMethodDefSize();

    /**
     * InitMethodDefを追加します。
     *
     * @param \seasar\container\impl\InitMethodDef $methodDef
     */
    public function addInitMethodDef(\seasar\container\impl\InitMethodDef $methodDef);

    /**
     * すべてのAspectDefを返します。
     *
     * @return array
     */
    public function getAspectDefs();

    /**
     * インデックスで指定されたAspectDefを返します。
     *
     * @param integer $index
     * @return \seasar\container\impl\AspectDef
     */
    public function getAspectDef($index);

    /**
     * AspectDefの数を返します。
     *
     * @return integer
     */
    public function getAspectDefSize();

    /**
     * AspectDefを追加します。
     *
     * @param \seasar\container\impl\AspectDef $aspectDef
     */
    public function addAspectDef(\seasar\container\impl\AspectDef $aspectDef);

    /**
     * インデックスで指定されたMetaDefを返します。
     *
     * @param integer $index
     * @return \seasar\container\impl\MetaDef
     */
    public function getMetaDef($index);

    /**
     * 名前で指定されたMetaDefを返します。
     *
     * @param string $name
     * @return array
     */
    public function getMetaDefs($name);

    /**
     * MetaDefを追加します。
     *
     * @param \seasar\container\impl\MetaDef $metaDef
     */
    public function addMetaDef(\seasar\container\impl\MetaDef $metaDef);

    /**
     * MetaDefの数を返します。
     *
     * @return integer
     */
    public function getMetaDefSize();

    /**
     * コンストラクタクロージャを設定します。
     *
     * @param \Closure $closure
     * @return void
     */
    public function setConstructClosure(\Closure $closure);

    /**
     * コンストラクタクロージャを返します。
     *
     * @return \Closure
     */
    public function getConstructClosure();

    /**
     * コンストラクタクロージャが設定されているかどうかを返します。
     *
     * @return boolean
     */
    public function hasConstructClosure();

}
