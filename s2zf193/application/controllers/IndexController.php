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
     * GET /base/url/index/item-list
     *
     * �R���|�[�l���g���N���X���Ŏ擾���܂��B
     * �R���|�[�l���g���N���X���Ŏ擾����ꍇ�́A
     *   APPLICATION_PATH/dicons/index/item-list.php
     * ���ł̓��ʂȐݒ�͕K�v����܂���B
     */
    public function itemListAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_ItemService')->findAllItem();
    }

    /**
     * GET /base/url/index/customer-list
     *
     * �R���|�[�l���g���ucustomer�v�Ŏ擾���܂��B
     * �R���|�[�l���g���̐ݒ�ɂ��ẮAAPPLICATION_PATH/dicons/index/customer-list.php���Q�Ɖ������B
     */
    public function customerListAction()
    {
        $this->view->dtos = $this->_helper->s2->customer->findAllCustomer();
    }

    /**
     * GET /base/url/index/order-by-item/id/10
     *
     * item_id����]���s�Z�b�g�A�e�̍s���擾���āA�������(Ordering)��Ԃ��܂��B
     */
    public function orderByItemAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_ItemService')->findOrderingByItemId((integer)$this->_request->getParam('id'));
    }

}

