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
     * コンポーネントをクラス名で取得します。
     * コンポーネントをクラス名で取得する場合は、
     *   APPLICATION_PATH/dicons/index/cd-list.php
     * 等での特別な設定は必要ありません。
     */
    public function cdListAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_SampleService')->findAllCd();
    }

    /**
     * コンポーネント名「service2」で取得します。
     * コンポーネント名の設定については、APPLICATION_PATH/dicons/index/cd-list2.phpを参照下さい。
     */
    public function cdList2Action()
    {
        $this->view->dtos = $this->_helper->s2->service2->findAllCd();
    }
}

