<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.extension.unit.phpunit2
 * @author klove
 */
class S2Container_S2PHPUnit2TestCase extends PHPUnit2_Framework_TestCase {

    private $container_;
    private $bindedFields_ = array();
    
	/**
	 * <p>
	 * テストケースが保有するS2コンテナを取得します。
	 * </p>
	 * 
	 * @return S2コンテナ
	 */
	public function getContainer() {
		return $this->container_;
	}

	/**
	 * <p>
	 * コンポーネント名を指定してS2コンテナからコンポーネントを取得 します。
	 * </p>
	 * 
	 * @param componentName
	 *            取得するコンポーネント名
	 * @return 指定した名前を持つコンポーネント
	 * @see S2Container::getComponent()
	 */
	public function getComponent($componentName) {
		return $this->container_->getComponent($componentName);
	}

	/**
	 * <p>
	 * コンポーネント名を指定してS2コンテナからコンポーネント定義を 取得します。
	 * </p>
	 * 
	 * @param componentName
	 *            取得するコンポーネント名
	 * @return 指定した名前を持つコンポーネント定義
	 * @see S2Container::getComponentDef()
	 */
	public function getComponentDef($componentName) {
		return $this->container_->getComponentDef($componentName);
	}

	/**
	 * <p>
	 * クラスをS2コンテナに名前付きコンポーネント定義として登録 します。
	 * </p>
	 * 
	 * @param object component 
	 * @param string componentName
	 * @see S2Container::register()
	 */
	public function register($component,$componentName="") {
		$this->container_->register($component, $componentName);
	}
				
    /**
     * Runs the bare test sequence.
     *
     * @access public
     */
    public function runBare() {
        //print __METHOD__ . " called.\n";
        $catchedException = NULL;

        $this->setUpContainer();
        $this->setUp();
        $this->setUpForEachTestMethod();
        $this->container_->init();
        $this->setUpAfterContainerInit();
        $this->bindFields();
        $this->setUpAfterBindFields();
        try {
            $this->runTest();
        }
        catch (Exception $e) {
            $catchedException = $e;
        }
        $this->tearDownBeforeUnbindFields();
        $this->unbindFields();
        $this->tearDownBeforeContainerDestroy();
        $this->tearDownContainer();
        $this->tearDownForEachTestMethod();
        $this->tearDown();

        if ($catchedException !== NULL) {
            throw $catchedException;
        }
    }

	/**
	 * <p>
	 * 設定ファイルのパスを指定して子コンテナをincludeします。
	 * </p>
	 * <p>
	 * パスはCLASSPATHで指定されているディレクトリをルートとする 設定ファイルの絶対パスか、ファイル名のみを指定します。
	 * </p>
	 * <p>
	 * ファイル名のみの場合、テストケースと同じパッケージにあるもの とします。
	 * </p>
	 * 
	 * @param path
	 *            子コンテナの設定ファイルのパス
	 */
	protected function includeDicon($path) {
		S2ContainerFactory::includeChild($this->container_,$path);
	}

    
	/**
	 * <p>
	 * コンテナをセットアップするメソッドです。
	 * </p>
	 * <p>
	 * 必要な場合にオーバーライドしてください。
	 * </p>
	 */
	protected function setUpContainer(){
		$this->container_ = new S2ContainerImpl();
		S2Container_SingletonS2ContainerFactory::setContainer($this->container_);    	
    }

	/**
	 * <p>
	 * setUp() 後に実行されるテストメソッドごとのセットアップ メソッドです。
	 * </p>
	 * <p>
	 * testXxx() というメソッドの場合、setUpXxx() という名前で セットアップメソッドを作成しておくと、自動的に実行されます。
	 * </p>
	 * 
	 */
	protected function setUpForEachTestMethod(){
		$targetName = $this->getTargetName();
		if ($targetName != "") {
			$this->invoke("setUp" . $targetName);
		}
	}    

	/**
	 * <p>
	 * コンテナ初期化後に実行されるセットアップメソッドです。
	 * </p>
	 * <p>
	 * 必要な場合にオーバーライドしてください。
	 * </p>
	 * 
	 */
	protected function setUpAfterContainerInit(){
	}

	/**
	 * <p>
	 * フィールドが設定された後に実行されるセットアップメソッドです。
	 * </p>
	 * <p>
	 * 必要な場合にオーバーライドしてください。
	 * </p>
	 * 
	 */
	protected function setUpAfterBindFields(){
	}

	/**
	 * <p>
	 * フィールドの設定が解除される前に実行される処理終了メソッドです。
	 * </p>
	 * <p>
	 * 必要な場合にオーバーライドしてください。
	 * </p>
	 */
	protected function tearDownBeforeUnbindFields(){
	}

	/**
	 * <p>
	 * コンテナ終了処理前に実行される終了処理メソッドです。
	 * </p>
	 * <p>
	 * 必要な場合にオーバーライドしてください。
	 * </p>
	 */
	protected function tearDownBeforeContainerDestroy(){
	}

	protected function tearDownContainer(){
		$this->container_->destroy();
		S2Container_SingletonS2ContainerFactory::setContainer(null);
		$this->container_ = null;
	}

	/**
	 * <p>
	 * tearDown() 前に実行されるテストメソッドごとの終了処理メソッド です。
	 * </p>
	 * <p>
	 * testXxx() というメソッドの場合、tearDownXxx() という名前で 終了処理メソッドを作成しておくと、自動的に実行されます。
	 * </p>
	 */
	protected function tearDownForEachTestMethod() {
		$targetName = $this->getTargetName();
		if ($targetName != "") {
			$this->invoke("tearDown" . $targetName);
		}
		
	}
			
	private function unbindFields() {
		foreach($this->bindedFields_ as $field){
    		$field->setValue($this, null);
		}
		$this->bindedFields_ = array();
	}
		
	private function bindFields() {
		$ref = new ReflectionClass($this);
        $props = $ref->getProperties();
        foreach($props as $prop){
			$this->bindField($prop);
		}
	}

	private function bindField(ReflectionProperty $field) {
		if ($this->isAutoBindable($field)) {
			$propName = $field->getName();
			if($this->getContainer()->hasComponentDef($propName)){
			    $field->setValue($this,$this->getComponent($propName));
			    array_push($this->bindedFields_,$field);
			}
		}
	}

	private function isAutoBindable(ReflectionProperty $field) {
		return !$field->isStatic() and $field->isPublic() and
		        !preg_match("/^PHPUnit2_/",$field->getDeclaringClass()->getName());
	}
		
	private function invoke($methodName) {
		try {
			$method = S2Container_ClassUtil::getMethod(new ReflectionClass($this),
			                               $methodName);
			S2Container_MethodUtil::invoke($method,$this, null);
		} catch (S2Container_NoSuchMethodRuntimeException $ignore) {
			//print "invoke ignored. [$methodName]\n";
		}
	}	

	private function getTargetName() {
		return substr($this->getName(),4);
	}	
}
?>