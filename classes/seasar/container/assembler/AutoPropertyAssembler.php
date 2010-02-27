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
 * プロパティアセンブラの自動版です。
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
class AutoPropertyAssembler extends ManualPropertyAssembler {

    /**
     * @see \seasar\container\assembler\ManualPropertyAssembler::__construct()
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * 指定されたcomponentに対して、 プロパティ・インジェクションやセッターメソッド・インジェクションを実行します。
     *
     * @param object $component
     */
    public function assemble($component) {
        parent::assemble($component);
        $componentDef = $this->getComponentDef();
        $container    = $componentDef->getContainer();
        $beanDesc = \seasar\beans\BeanDescFactory::getBeanDesc($componentDef->getComponentClass());
        $propDescs = $beanDesc->getTypehintPropertyDescs();
        foreach ($propDescs as $propDesc) {
            if ($componentDef->hasPropertyDef($propDesc->getPropertyName())) {
                continue;
            }
            $value   = null;
            try {
                if ($propDesc->isArrayAcceptable()) {
                    $value = $container->findComponents($propDesc->getTypehint());
                } else {
                    $value = $container->getComponent($propDesc->getTypehint());
                }
            } catch (\seasar\container\exception\ComponentNotFoundRuntimeException $e) {
                \seasar\log\S2Logger::getInstance(__CLASS__)->info("no component found for typehint property. [{$componentDef->getComponentClass()->getName()}::\${$propDesc->getPropertyName()}]", __METHOD__);
                continue;
            } catch (\seasar\container\exception\TooManyRegistrationRuntimeException $e) {
                \seasar\log\S2Logger::getInstance(__CLASS__)->info("too many component found for typehint property. ignored. [{$componentDef->getComponentClass()->getName()}::\${$propDesc->getPropertyName()}]", __METHOD__);
                continue;
            }
            $propDesc->setValue($component, $value);
        }
    }
}
