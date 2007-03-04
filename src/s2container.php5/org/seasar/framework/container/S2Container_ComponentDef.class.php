<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * S2コンテナが管理するコンポーネントの定義を表すインタフェースです。
 * <p>
 * コンポーネント定義は、 コンポーネントの管理に必要な以下の情報を保持します。
 * <ul>
 * <li>ライフサイクル<br>
 *     コンポーネントのスコープや、生成と消滅については、 このコンポーネントの{@link http://s2container.php5.seasar.org/DIContainer.html#InstanceMode インスタンス定義}で設定します。
 *     生成については、 {@link http://s2container.php5.seasar.org/php.html#compoonent コンポーネント生成のPHP式}により指定することも可能です。</li>
 * <li>依存性注入(Dependency Injection)<br>
 *     このコンポーネントが依存する他のコンポーネントやパラメータは、 {@link S2Container_ArgDef コンストラクタ引数定義}、
 * {@link S2Container_InitMethodDef 初期化メソッド定義}、 {@link S2Container_PropertyDef プロパティ定義}などにより設定します。</li>
 * <li>アスペクト<br>
 *     このコンポーネントの{@link S2Container_AspectDef アスペクト定義}により設定します。</li>
 * <li>メタデータ<br>
 *     {@link S2Container_MetaDef メタデータ定義}により、 コンポーネントに付加情報を設定できます。 メタデータは、
 * 特殊なコンポーネントであることを識別する場合などに利用します。</li>
 * </ul>
 * </p>
 * 
 * @see S2Container_ArgDef
 * @see S2Container_PropertyDef
 * @see S2Container_InitMethodDef
 * @see S2Container_DestroyMethodDef
 * @see S2Container_AspectDef
 * @see S2Container_MetaDef
 * 
 * @copyright  2005-2007 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_ComponentDef
    extends S2Container_ArgDefAware,
        S2Container_PropertyDefAware,
        S2Container_InitMethodDefAware,
        S2Container_DestroyMethodDefAware,
        S2Container_AspectDefAware,
        S2Container_MetaDefAware,
        S2Container_InterTypeDefAware
{

    /**
     * 定義に基づいてコンポーネントを返します。
     * 
     * @return object コンポーネント
     * @throws S2Container_TooManyRegistrationRuntimeException
     *             コンポーネント定義が重複している場合
     * @throws S2Container_CyclicReferenceRuntimeException
     *             コンポーネント間に循環参照がある場合
     * 
     * @see S2Container_TooManyRegistrationComponentDef
     */
    public function getComponent();
        
    /**
     * 外部コンポーネント<b>outerComponent</b>に対し、
     * {@link S2Container_ComponentDef コンポーネント定義}に基づいて、 S2コンテナ上のコンポーネントをインジェクションします。
     * 
     * @param object outerComponent 外部コンポーネント
     */
    public function injectDependency($outerComponent);

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
    public function setContainer(S2Container $container);

    /**
     * 定義上のクラスを返します。 diconファイルの<b><component/></b>タグにおける、
     * <b>class</b>属性で指定されたクラスを表します。 自動バインディングされる際には、
     * このクラス(インターフェース)が使用されます。
     * 
     * @return ReflectionClass 定義上のクラス
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
     * @see S2Container_ComponentDef::getComponentClass()
     * @return ReflectionClass 定義上のクラス
     */
    public function getConcreteClass();

    /**
     * 自動バインディングモードを返します。
     * 
     * @return integer 自動バインディングモード
     */
    public function getAutoBindingMode();

    /**
     * 自動バインディングモードを設定します。
     * 
     * @param integer $mode 自動バインディングモード
     */
    public function setAutoBindingMode($mode);

    /**
     * インスタンスモードを返します。
     * 
     * @return integer インスタンスモード
     */
    public function getInstanceMode();

    /**
     * インスタンスモードを設定します。
     * 
     * @param integer $mode インスタンスモード
     */
    public function setInstanceMode($mode);
    
    /**
     * コンポーネントを生成するPHP式を返します。
     * 
     * @return string コンポーネント生成PHP式
     */
    public function getExpression();

    /**
     * コンポーネントを生成するPHP式を設定します。
     * 
     * @param string $expression コンポーネント生成PHP式
     */
    public function setExpression($expression);

    /**
     * コンポーネント定義を初期化します。
     * <p>
     * コンポーネントインスタンスモードが<b>singleton</b>の場合には、
     * {@link S2Container_AspectDef アスペクト}を適用したインスタンスの生成、 配備、 プロパティ設定の後に、
     * {@link S2Container_InitMethodDef initMethod}が呼ばれます。
     * </p>
     * 
     * @see S2Container_SingletonComponentDeployer::init()
     */
    public function init();

    /**
     * コンポーネント定義を破棄します。
     * <p>
     * コンポーネントインスタンスモードが<b>singleton</b>の場合には、
     * {@link S2Container_DestroyMethodDef destroyMethod}が呼ばれます。
     * </p>
     * 
     * @see S2Container_SingletonComponentDeployer::destroy()
     */
    public function destroy();
    
    /**
     * コンポーネントクラス(ReflectionClass)を初期化(new)します。
     * 
     * @param integer $mode 初期化モード
     */
    public function reconstruct($mode = 
                           S2Container_ComponentDef::RECONSTRUCT_NORMAL);
    /**
     * コンポーネントクラスの初期化(new)において、初期化済みでない場合のみ初期化を行います。
     * @see S2Container_ComponentDef::reconstruct()
     */
    const RECONSTRUCT_NORMAL = 0;

    /**
     * コンポーネントクラスの初期化(new)において、必ず初期化を行います。
     * @see S2Container_ComponentDef::reconstruct()
     */
    const RECONSTRUCT_FORCE = 1;
}
?>
