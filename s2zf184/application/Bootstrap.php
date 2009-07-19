<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload()
    {
        require_once('Zend/Loader/Autoloader.php');
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);
        new Zend_Application_Module_Autoloader(array('namespace' => '', 'basePath'  => dirname(__FILE__)));
    }

    protected function _initHelper()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Seasar_Zf_Controller_S2ActionHelper);
    }
}


// ƒ‚ƒWƒ…[ƒ‹\¬‚Ìê‡
// class Sample_Bootstrap extends Zend_Application_Module_Bootstrap {}
// application.ini ‚É "resources.modules =" ‚ğ’Ç‰Á
