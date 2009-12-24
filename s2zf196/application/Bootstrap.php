<?php
/**
 * Bootstrapクラスの設定はapplication.iniの設定よりも優先される。
 * Bootstrapクラスとapplication.iniに同一Resourceの設定が存在した場合は、
 * Bootstrapクラスの設定が有効となる。
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload()
    {
        require_once('Zend/Loader/Autoloader.php');
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);
        new Zend_Application_Module_Autoloader(array('namespace' => '', 'basePath'  => dirname(__FILE__)));

        //require_once(APPLICATION_PATH . '/configs/s2erd_autoload.php');
    }

    protected function _initActionHelper()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Seasar_Zf_Controller_S2ActionHelper);
    }

    protected function _initView()
    {
        $view = new Zend_View();
        $view->doctype('HTML4_LOOSE');
        $view->headTitle('My First S2ZF');
        $view->setEncoding('UTF-8');
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        return $view;
    }

}


// モジュール構成の場合
// class Sample_Bootstrap extends Zend_Application_Module_Bootstrap {}
// application.ini に "resources.modules =" を追加
