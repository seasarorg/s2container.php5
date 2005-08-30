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
 * �R���|�[�l���g���Ǘ�����DI�R���e�i�̃C���^�[�t�F�[�X�ł��B
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface S2Container extends MetaDefAware{

    /**
     * �L�[���w�肵�ăR���|�[�l���g���擾���܂��B
     * 
     * �L�[��������̏ꍇ�A��v����R���|�[�l���g�������R���|�[�l���g��
     * �擾���܂��B
     * �L�[���N���X���܂��̓C���^�[�t�F�[�X���̏ꍇ�A
     * �u�R���|�[�l���g instanceof �L�[�v
     * �𖞂����R���|�[�l���g���擾���܂��B
     *
     * @param string �R���|�[�l���g���擾���邽�߂̃L�[
     * @return object
     * @throws ComponentNotFoundRuntimeException �R���|�[�l���g��������Ȃ��ꍇ
     * @throws TooManyRegistrationRuntimeException �������O�A�܂��͓����N���X�ɕ����̃R���|�[�l���g���o�^����Ă���ꍇ
     * @throws CyclicReferenceRuntimeException constructor injection�ŃR���|�[�l���g�̎Q�Ƃ��z���Ă���ꍇ
     */
    public function getComponent($componentKey);

    /**
     * �O���R���|�[�l���g�ɃZ�b�^�[�E�C���W�F�N�V�����A���\�b�h�E�C���W�F�N�V���������s���܂��B
     * 
     * componentClass���L�[�Ƃ��ăR���|�[�l���g��`���擾���܂��B
     * instance���[�h��"outer"�ƒ�`���ꂽ�R���|�[�l���g�̂ݗL���ł��B
     * �u�R���|�[�l���g instanceof �O���R���|�[�l���g�v
     * �𖞂����O���R���|�[�l���g��`�𗘗p���܂��B
     *
     * @param object
     * @param string �O���R���|�[�l���g��`�̃L�[ (���O)
     * @throws ClassUnmatchRuntimeException �u�O���R���|�[�l���g instanceof �擾�����R���|�[�l���g�̃N���X�v��false��Ԃ��ꍇ
     */
    public function injectDependency($outerComponent,$componentName="");
    
    /**
     * �I�u�W�F�N�g�𖼑O�t���R���|�[�l���g�Ƃ��ēo�^���܂��B
     *
     * @param object
     * @param string �R���|�[�l���g��
     */
    public function register($component, $componentName="");

    /**
     * �R���|�[�l���g��`�̐����擾���܂��B
     *
     * @return int �R���|�[�l���g��`�̐�
     */
    public function getComponentDefSize();

    /**
     * �w�肵���L�[�ɑΉ�����R���|�[�l���g��`���擾���܂��B
     *
     * @param int �L�[
     * @return ComponentDef �R���|�[�l���g��`
     * @throws ComponentNotFoundRuntimeException �R���|�[�l���g��`��������Ȃ��ꍇ
     */
    public function getComponentDef($index);

    /**
     * �w�肵���L�[�ɑΉ�����R���|�[�l���g��`�����ǂ������肵�܂��B
     *
     * @param string �L�[
     * @return boolean ���݂���Ȃ�true
     */
    public function hasComponentDef($componentKey);
    
    /**
     * root�̃R���e�i�ŁApath�ɑΉ�����R���e�i�����Ƀ��[�h����Ă��邩��Ԃ��܂��B
     *
     * @param string �p�X
     * @return boolean ���[�h����Ă���Ȃ�true
     */
    public function hasDescendant($path);

    /**
     * root�̃R���e�i�ŁA�w�肵���p�X�ɑΉ����郍�[�h�ς݂̃R���e�i���擾���܂��B
     *
     * @param string �p�X
     * @return S2Container �R���e�i
     * @throws ContainerNotRegisteredRuntimeException �R���e�i��������Ȃ��ꍇ
     */    
    public function getDescendant($path);
    
    /**
     * root�̃R���e�i�ɁA���[�h�ς݂̃R���e�i��o�^���܂��B
     *
     * @param S2Container ���[�h�ς݂̃R���e�i
     */
    public function registerDescendant(S2Container $descendant);

    /**
     * �q�R���e�i��include���܂��B
     *
     * @param S2Container include����q�R���e�i
     */
    public function includeChild(S2Container $child);
    
    /**
     * �q�R���e�i�̐����擾���܂��B
     *
     * @return int �q�R���e�i�̐�
     */
    public function getChildSize();
    
    /**
     * �ԍ����w�肵�Ďq�R���e�i���擾���܂��B
     *
     * @param int �q�R���e�i�̔ԍ�
     * @return S2Container �q�R���e�i
     */
    public function getChild($index);

    /**
     * �R���e�i�����������܂��B
     * 
     * �q�R���e�i�����ꍇ�A�q�R���e�i��S�ď�����������A���������������܂��B
     */
    public function init();

    /**
     * �R���e�i�̏I�������������Ȃ��܂��B
     * 
     * �q�R���e�i�����ꍇ�A�����̏I�����������s������A
     * �q�R���e�i�S�Ă̏I���������s���܂��B
     */
    public function destroy();

    /**
     * ���O��Ԃ��擾���܂��B
     *
     * @return string ���O���
     */    
    public function getNamespace();

    /**
     * ���O��Ԃ��Z�b�g���܂��B
     *
     * @param string �Z�b�g���閼�O���
     */    
    public function setNamespace($namespace);

    /**
     * �ݒ�t�@�C���̃p�X���擾���܂��B
     *
     * @return string �ݒ�t�@�C���̃p�X
     */    
    public function getPath();

    /**
     * �ݒ�t�@�C���̃p�X���Z�b�g���܂��B
     *
     * @param string �Z�b�g����ݒ�t�@�C���̃p�X
     */    
    public function setPath($path);

    /**
     * ���[�g�̃R���e�i���擾���܂��B
     *
     * @return S2Container ���[�g�̃R���e�i
     */
    public function getRoot();

    /**
     * ���[�g�̃R���e�i���Z�b�g���܂��B
     *
     * @param S2Container �Z�b�g���郋�[�g�̃R���e�i
     */    
    public function setRoot(S2Container $root);

}
?>
