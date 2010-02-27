<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 * コンポーネントアセンブラの抽象クラスです。
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
abstract class AbstractAssembler {

    /**
     * @var \seasar\container\ComponentDef
     */
    private $componentDef;

    /**
     * AbstractAssemblerのコンストラクタです。
     * @param \seasar\container\ComponentDef $componentDef
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        $this->componentDef = $componentDef;
    }

    /**
     * ComponentDefを返します。
     * @return \seasar\container\ComponentDef
     */
    protected final function getComponentDef() {
        return $this->componentDef;
    }

    /**
     * コンストラクタ、メソッド引数のReflectionParameterも用いて、コンストラクタ、メソッド引数を
     * 自動的に組み立てます。
     * @param array $propRefs
     * @return array
     */
    protected function getArgs(array $propRefs) {
        $args = array();
        $container = $this->getComponentDef()->getContainer();
        foreach($propRefs as $propRef) {
            if ($propRef->getClass() !== null and
                $container->hasComponentDef($propRef->getClass()->getName())) {
                $args[] = $container->getComponent($propRef->getClass()->getName());
            } else {
                if ($propRef->isOptional()) {
                    $args[] = $propRef->getDefaultValue();
                } else {
                    $args[] = null;
                }
            }
        }
        return $args;
    }

    /**
     * コンストラクタ、メソッドの引数値を取得します。
     * 配列のタイプヒントがされている場合に、TooManyRegistrationRuntimeExceptionが発生した場合は、
     * すべてのコンポーネントを配列に格納して返します。
     * @param \seasar\container\impl\ArgDef $argDef
     * @param boolean $isArrayAcceptable
     * @return mixed
     */
    protected function getArgument(\seasar\container\impl\ArgDef $argDef, $isArrayAcceptable = false) {
        $value  = null;
        try {
            $value = $argDef->getValue();
            if ($isArrayAcceptable and !is_array($value)) {
                $value = array($value);
            }
        } catch(\seasar\container\exception\TooManyRegistrationRuntimeException $e) {
            if ($isArrayAcceptable) {
                $childComponentDefs = $argDef->getChildComponentDef()->getComponentDefs();
                $value = array();
                foreach ($childComponentDefs as $childComponentDef) {
                    $value[] = $childComponentDef->getComponent();
                }
            } else {
                throw $e;
            }
        }
        return $value;
    }
}
