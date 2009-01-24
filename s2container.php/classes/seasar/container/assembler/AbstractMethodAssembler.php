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
 * メソッドアセンブラの抽象クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
abstract class AbstractMethodAssembler extends AbstractAssembler {

    /**
     * @see \seasar\container\assembler\AbstractAssembler::__construct()
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * メソッドインジェクションで定義されたメソッドを呼び出します。
     * expressionが定義されている場合は、コンポーネントのインスタンスをコンテキストに登録して
     * eval関数を実行します。
     *
     * @param object $component
     * @param \seasar\container\MethodDef $methodDef
     */
    protected function invoke($component, \seasar\container\MethodDef $methodDef) {
        if ($methodDef->getMethodName() == null) {
            \seasar\util\EvalUtil::execute($methodDef->getExpression(), array('component' => $component));
        } else {
            $refMethod = $this->getComponentDef()->getComponentClass()->getMethod($methodDef->getMethodName());
            $refParams = $refMethod->getParameters();
            $args = array();
            $argDefs = $methodDef->getArgDefs();
            $o = count($argDefs);
            if (0 < $o) {
                for ($i = 0; $i < $o; ++$i) {
                    if (isset($refParams[$i]) and $refParams[$i]->isArray()) {
                        $args[] = $this->getArgument($argDefs[$i], true);
                    } else {
                        $args[] = $this->getArgument($argDefs[$i], false);
                    }
                }
            } else {
                $args = $this->getArgs($refParams);
            }
            \seasar\util\MethodUtil::invoke($refMethod, $component, $args);
        }
    }
}
