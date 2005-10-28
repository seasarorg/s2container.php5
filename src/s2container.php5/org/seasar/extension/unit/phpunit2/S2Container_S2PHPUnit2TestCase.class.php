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
	 * �e�X�g�P�[�X���ۗL����S2�R���e�i���擾���܂��B
	 * </p>
	 * 
	 * @return S2�R���e�i
	 */
	public function getContainer() {
		return $this->container_;
	}

	/**
	 * <p>
	 * �R���|�[�l���g�����w�肵��S2�R���e�i����R���|�[�l���g���擾 ���܂��B
	 * </p>
	 * 
	 * @param componentName
	 *            �擾����R���|�[�l���g��
	 * @return �w�肵�����O�����R���|�[�l���g
	 * @see S2Container::getComponent()
	 */
	public function getComponent($componentName) {
		return $this->container_->getComponent($componentName);
	}

	/**
	 * <p>
	 * �R���|�[�l���g�����w�肵��S2�R���e�i����R���|�[�l���g��`�� �擾���܂��B
	 * </p>
	 * 
	 * @param componentName
	 *            �擾����R���|�[�l���g��
	 * @return �w�肵�����O�����R���|�[�l���g��`
	 * @see S2Container::getComponentDef()
	 */
	public function getComponentDef($componentName) {
		return $this->container_->getComponentDef($componentName);
	}

	/**
	 * <p>
	 * �N���X��S2�R���e�i�ɖ��O�t���R���|�[�l���g��`�Ƃ��ēo�^ ���܂��B
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
	 * �ݒ�t�@�C���̃p�X���w�肵�Ďq�R���e�i��include���܂��B
	 * </p>
	 * <p>
	 * �p�X��CLASSPATH�Ŏw�肳��Ă���f�B���N�g�������[�g�Ƃ��� �ݒ�t�@�C���̐�΃p�X���A�t�@�C�����݂̂��w�肵�܂��B
	 * </p>
	 * <p>
	 * �t�@�C�����݂̂̏ꍇ�A�e�X�g�P�[�X�Ɠ����p�b�P�[�W�ɂ������ �Ƃ��܂��B
	 * </p>
	 * 
	 * @param path
	 *            �q�R���e�i�̐ݒ�t�@�C���̃p�X
	 */
	protected function includeDicon($path) {
		S2ContainerFactory::includeChild($this->container_,$path);
	}

    
	/**
	 * <p>
	 * �R���e�i���Z�b�g�A�b�v���郁�\�b�h�ł��B
	 * </p>
	 * <p>
	 * �K�v�ȏꍇ�ɃI�[�o�[���C�h���Ă��������B
	 * </p>
	 */
	protected function setUpContainer(){
		$this->container_ = new S2ContainerImpl();
		S2Container_SingletonS2ContainerFactory::setContainer($this->container_);    	
    }

	/**
	 * <p>
	 * setUp() ��Ɏ��s�����e�X�g���\�b�h���Ƃ̃Z�b�g�A�b�v ���\�b�h�ł��B
	 * </p>
	 * <p>
	 * testXxx() �Ƃ������\�b�h�̏ꍇ�AsetUpXxx() �Ƃ������O�� �Z�b�g�A�b�v���\�b�h���쐬���Ă����ƁA�����I�Ɏ��s����܂��B
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
	 * �R���e�i��������Ɏ��s�����Z�b�g�A�b�v���\�b�h�ł��B
	 * </p>
	 * <p>
	 * �K�v�ȏꍇ�ɃI�[�o�[���C�h���Ă��������B
	 * </p>
	 * 
	 */
	protected function setUpAfterContainerInit(){
	}

	/**
	 * <p>
	 * �t�B�[���h���ݒ肳�ꂽ��Ɏ��s�����Z�b�g�A�b�v���\�b�h�ł��B
	 * </p>
	 * <p>
	 * �K�v�ȏꍇ�ɃI�[�o�[���C�h���Ă��������B
	 * </p>
	 * 
	 */
	protected function setUpAfterBindFields(){
	}

	/**
	 * <p>
	 * �t�B�[���h�̐ݒ肪���������O�Ɏ��s����鏈���I�����\�b�h�ł��B
	 * </p>
	 * <p>
	 * �K�v�ȏꍇ�ɃI�[�o�[���C�h���Ă��������B
	 * </p>
	 */
	protected function tearDownBeforeUnbindFields(){
	}

	/**
	 * <p>
	 * �R���e�i�I�������O�Ɏ��s�����I���������\�b�h�ł��B
	 * </p>
	 * <p>
	 * �K�v�ȏꍇ�ɃI�[�o�[���C�h���Ă��������B
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
	 * tearDown() �O�Ɏ��s�����e�X�g���\�b�h���Ƃ̏I���������\�b�h �ł��B
	 * </p>
	 * <p>
	 * testXxx() �Ƃ������\�b�h�̏ꍇ�AtearDownXxx() �Ƃ������O�� �I���������\�b�h���쐬���Ă����ƁA�����I�Ɏ��s����܂��B
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