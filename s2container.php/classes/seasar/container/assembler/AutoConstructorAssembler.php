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
 * コンストラクタアセンブラの自動版です。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class AutoConstructorAssembler extends ManualConstructorAssembler {

    /**
     * 自動コンストラクタ・インジェクションを実行して、 組み立てたコンポーネントを返します。
     *
     * @return object
     */
    public function assemble() {
        $componentDef = $this->getComponentDef();
        if ($componentDef->hasConstructClosure() || $componentDef->getArgDefSize() > 0) {
            return parent::assemble();
        }
        $args = array();
        $refMethod = $componentDef->getComponentClass()->getConstructor();
        if ($refMethod instanceof \ReflectionMethod) {
            $args = $this->getArgs($refMethod->getParameters());
        }
        return \seasar\container\util\ConstructorUtil::newInstance($componentDef, $args);
    }
}
