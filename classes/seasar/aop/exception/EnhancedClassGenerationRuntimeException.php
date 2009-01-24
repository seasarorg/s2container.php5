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
 * Enhancedクラス生成時に対象クラスがS2AOPが用意するプロパティやメソッドを持って
 * いる場合にスローされる例外。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop.exception
 * @author    klove
 */
namespace seasar\aop\exception;
class EnhancedClassGenerationRuntimeException extends \seasar\exception\S2RuntimeException {

    /**
     * @var string Enhance対象クラス名
     */
    private $className = null;

    /**
     * @var array overloadとなるメソッド群
     */
    private $orverloads = null;

    /**
     * @param string $className Enhance対象クラス名
     * @param array  $overload  overloadとなるメソッド群
     */
    public function __construct($className, array $overloads) {
        $this->className = $className;
        $this->overloads = $overloads;
        parent::__construct(203, array($className, implode(',', $overloads)));
    }

    /**
     * return string Enhance対象クラス名
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * @param return array overloadとなるメソッド群
     */
    public function getOverloads() {
        return $this->overloads;
    }
}
