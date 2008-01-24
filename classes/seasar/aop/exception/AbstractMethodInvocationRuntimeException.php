<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * S2AOPがenhanceしたクラスのメソッド実行において、対象のメソッドがAbstractの場合に
 * スローされる例外クラス。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop.exception
 * @author    klove
 */
namespace seasar::aop::exception;
class AbstractMethodInvocationRuntimeException extends seasar::exception::S2RuntimeException {
    /**
     * @var string 実行対象となったメソッド名
     */
    private $methodName = null;

    /**
     * @param string アスペクト対象のクラス名
     * @param string 実行対象となったメソッド名
     */
    public function __construct($className, $methodName) {
        $this->methodName = $methodName;
        parent::__construct(202, array($className, $methodName));
    }

    /**
     * @return string 実行対象となったメソッド名
     */
    public function getMethodName() {
        return $this->methodName;
    }
}
