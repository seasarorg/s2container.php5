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
     * GET /s2zf/index/add
     */
    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        echo $this->_helper->s2('Service_Calc')->add(1, 2);
    }

    /**
     * GET /s2zf/index/add2
     */
    public function add2Action()
    {
        $this->_helper->viewRenderer->setNoRender();
        echo $this->_helper->s2->calc->add(1, 2);
    }

    /**
     * GET /s2zf/index/bug-list
     */
    public function bugListAction()
    {
        $this->view->dtos = $this->_helper->s2('Service_Sample')->fetchAllBugs();
    }

    /**
     * GET /s2zf/index/product-bug-descs
     */
    public function productBugDescsAction()
    {
        $this->view->dtos = $this->_helper->s2->sample->fetchProductBugDescriptions();
    }
}

