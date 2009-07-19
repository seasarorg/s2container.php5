<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
/**
 * S2Containerを使用するZend_Controllerのアクションヘルパーです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.1
 * @package   Seasar_Zf_Controller
 * @author    klove
 */
class Seasar_Zf_Controller_S2ActionHelper extends Zend_Controller_Action_Helper_Abstract {
    public function preDispatch() {
        $request    = $this->getRequest();
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        $dirs       = $this->getFrontController()->getControllerDirectory();
        if (empty($module) || !isset($dirs[$module])) {
            $module = $this->getFrontController()->getDispatcher()->getDefaultModule();
        }
        $moduleDir = dirname($dirs[$module]);
        $actFile = "$moduleDir/dicons/$controller/$action.php";
        if (file_exists($actFile)) {
            require_once(APPLICATION_PATH . '/configs/s2.php');
            require($actFile);
        }
    }

    public function getName() {
        return 'S2';
    }

    public function direct($key) {
        require_once(APPLICATION_PATH . '/configs/s2.php');
        return \seasar\container\S2ApplicationContext::get($key);
    }

    public function __get($name) {
        return $this->direct($name);
    }
}
