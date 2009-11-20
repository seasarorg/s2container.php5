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
     * GET /s2zf/index/item-list
     *
     * コンポーネントをクラス名で取得します。
     * コンポーネントをクラス名で取得する場合は、
     *   APPLICATION_PATH/dicons/index/item-list.php
     * 等での特別な設定は必要ありません。
     */
    public function itemListAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_ItemService')->findAllItem();
    }

    /**
     * GET /s2zf/index/customer-list
     *
     * コンポーネント名「customer」で取得します。
     * コンポーネント名の設定については、APPLICATION_PATH/dicons/index/customer-list.phpを参照下さい。
     */
    public function customerListAction()
    {
        $this->view->dtos = $this->_helper->s2->customer->findAllCustomer();
    }

    /**
     * GET /s2zf/index/order-by-item/id/10
     *
     * item_idから従属行セット、親の行を取得して、注文情報(Ordering)を返します。
     */
    public function orderByItemAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_ItemService')->findOrderingByItemId((integer)$this->_request->getParam('id'));
    }

}

