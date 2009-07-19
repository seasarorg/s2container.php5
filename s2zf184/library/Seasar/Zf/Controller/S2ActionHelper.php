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

    /**
     * モジュールディレクトリのdiconsディレクトリ以下にあるS2Container設定ファイルを読み込みます。
     * 次のディレクトリ構成をとります。
     *   - module directory/
     *     +- dicons/
     *        +- contorller name/
     *           +- action name.php
     *
     * S2Container設定ファイル内では、次の変数が使用可能です。
     *   - @var Zend_Controller_Request_Abstract $request
     *   - @var string $module モジュール名
     *   - @var string $controller コントローラ名
     *   - @var string $action アクション名
     *   - @var string $moduleDir モジュールディレクトリパス
     *
     * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
     */
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

    /**
     * @see Zend_Controller_Action_Helper_Abstract::getName()
     */
    public function getName() {
        return 'S2';
    }

    /**
     * @see Zend_Controller_Action_Helper_Abstract::direct()
     * @param string $key コンポーネントキー
     * @return object
     */
    public function direct($key) {
        require_once(APPLICATION_PATH . '/configs/s2.php');
        return \seasar\container\S2ApplicationContext::get($key);
    }

    /**
     * @param string $key コンポーネントキー
     * @return object
     */
    public function __get($key) {
        return $this->direct($key);
    }
}
