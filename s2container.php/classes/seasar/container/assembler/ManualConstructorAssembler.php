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
 * 手動で設定されたものだけを対象にするプロパティアセンブラです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class ManualConstructorAssembler extends AbstractAssembler {

    /**
     * @see \seasar\container\assembler\AbstractAssembler::__construct()
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * 手動コンストラクタ・インジェクションを実行して、 組み立てたコンポーネントを返します。
     *
     * @return object
     */
    public function assemble() {
        $args           = array();
        $refParams      = array();
        $componentDef   = $this->getComponentDef();
        $refConstructor = $componentDef->getComponentClass()->getConstructor();
        if ($refConstructor instanceof \ReflectionMethod) {
            $refParams = $refConstructor->getParameters();
        }
        $argDefs = $componentDef->getArgDefs();
        $o = count($argDefs);
        for ($i = 0; $i < $o; ++$i) {
            if (isset($refParams[$i]) and $refParams[$i]->isArray()) {
                $args[] = $this->getArgument($argDefs[$i], true);
            } else {
                $args[] = $this->getArgument($argDefs[$i], false);
            }
        }
        return \seasar\container\util\ConstructorUtil::newInstance($componentDef, $args);
    }
}
