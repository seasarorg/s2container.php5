<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    /**
     * �R���|�[�l���g���N���X���Ŏ擾���܂��B
     * �R���|�[�l���g���N���X���Ŏ擾����ꍇ�́A
     *   APPLICATION_PATH/dicons/index/cd-list.php
     * ���ł̓��ʂȐݒ�͕K�v����܂���B
     */
    public function cdListAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_SampleService')->findAllCd();
    }

    /**
     * �R���|�[�l���g���uservice2�v�Ŏ擾���܂��B
     * �R���|�[�l���g���̐ݒ�ɂ��ẮAAPPLICATION_PATH/dicons/index/cd-list2.php���Q�Ɖ������B
     */
    public function cdList2Action()
    {
        $this->view->dtos = $this->_helper->s2->service2->findAllCd();
    }
}

