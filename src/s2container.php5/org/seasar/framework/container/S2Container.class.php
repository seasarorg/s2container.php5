<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * コンポーネントを管理するDIコンテナのインターフェースです。
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface S2Container extends MetaDefAware{

    /**
     * キーを指定してコンポーネントを取得します。
     * 
     * キーが文字列の場合、一致するコンポーネント名を持つコンポーネントを
     * 取得します。
     * キーがクラス名またはインターフェース名の場合、
     * 「コンポーネント instanceof キー」
     * を満たすコンポーネントを取得します。
     *
     * @param string コンポーネントを取得するためのキー
     * @return object
     * @throws ComponentNotFoundRuntimeException コンポーネントが見つからない場合
     * @throws TooManyRegistrationRuntimeException 同じ名前、または同じクラスに複数のコンポーネントが登録されている場合
     * @throws CyclicReferenceRuntimeException constructor injectionでコンポーネントの参照が循環している場合
     */
    public function getComponent($componentKey);

    /**
     * 外部コンポーネントにセッター・インジェクション、メソッド・インジェクションを実行します。
     * 
     * componentClassをキーとしてコンポーネント定義を取得します。
     * instanceモードが"outer"と定義されたコンポーネントのみ有効です。
     * 「コンポーネント instanceof 外部コンポーネント」
     * を満たす外部コンポーネント定義を利用します。
     *
     * @param object
     * @param string 外部コンポーネント定義のキー (名前)
     * @throws ClassUnmatchRuntimeException 「外部コンポーネント instanceof 取得したコンポーネントのクラス」がfalseを返す場合
     */
    public function injectDependency($outerComponent,$componentName="");
    
    /**
     * オブジェクトを名前付きコンポーネントとして登録します。
     *
     * @param object
     * @param string コンポーネント名
     */
    public function register($component, $componentName="");

    /**
     * コンポーネント定義の数を取得します。
     *
     * @return int コンポーネント定義の数
     */
    public function getComponentDefSize();

    /**
     * 指定したキーに対応するコンポーネント定義を取得します。
     *
     * @param int キー
     * @return ComponentDef コンポーネント定義
     * @throws ComponentNotFoundRuntimeException コンポーネント定義が見つからない場合
     */
    public function getComponentDef($index);

    /**
     * 指定したキーに対応するコンポーネント定義を持つどうか判定します。
     *
     * @param string キー
     * @return boolean 存在するならtrue
     */
    public function hasComponentDef($componentKey);
    
    /**
     * rootのコンテナで、pathに対応するコンテナが既にロードされているかを返します。
     *
     * @param string パス
     * @return boolean ロードされているならtrue
     */
    public function hasDescendant($path);

    /**
     * rootのコンテナで、指定したパスに対応するロード済みのコンテナを取得します。
     *
     * @param string パス
     * @return S2Container コンテナ
     * @throws ContainerNotRegisteredRuntimeException コンテナが見つからない場合
     */    
    public function getDescendant($path);
    
    /**
     * rootのコンテナに、ロード済みのコンテナを登録します。
     *
     * @param S2Container ロード済みのコンテナ
     */
    public function registerDescendant(S2Container $descendant);

    /**
     * 子コンテナをincludeします。
     *
     * @param S2Container includeする子コンテナ
     */
    public function includeChild(S2Container $child);
    
    /**
     * 子コンテナの数を取得します。
     *
     * @return int 子コンテナの数
     */
    public function getChildSize();
    
    /**
     * 番号を指定して子コンテナを取得します。
     *
     * @param int 子コンテナの番号
     * @return S2Container 子コンテナ
     */
    public function getChild($index);

    /**
     * コンテナを初期化します。
     * 
     * 子コンテナを持つ場合、子コンテナを全て初期化した後、自分を初期化します。
     */
    public function init();

    /**
     * コンテナの終了処理をおこないます。
     * 
     * 子コンテナを持つ場合、自分の終了処理を実行した後、
     * 子コンテナ全ての終了処理を行います。
     */
    public function destroy();

    /**
     * 名前空間を取得します。
     *
     * @return string 名前空間
     */    
    public function getNamespace();

    /**
     * 名前空間をセットします。
     *
     * @param string セットする名前空間
     */    
    public function setNamespace($namespace);

    /**
     * 設定ファイルのパスを取得します。
     *
     * @return string 設定ファイルのパス
     */    
    public function getPath();

    /**
     * 設定ファイルのパスをセットします。
     *
     * @param string セットする設定ファイルのパス
     */    
    public function setPath($path);

    /**
     * ルートのコンテナを取得します。
     *
     * @return S2Container ルートのコンテナ
     */
    public function getRoot();

    /**
     * ルートのコンテナをセットします。
     *
     * @param S2Container セットするルートのコンテナ
     */    
    public function setRoot(S2Container $root);

}
?>
