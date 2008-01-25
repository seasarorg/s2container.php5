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
 * 手動で設定されたものだけを対象にするプロパティアセンブラです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar::container::assembler;
class ManualPropertyAssembler extends AbstractAssembler {

    /**
     * @see seasar::container::assembler::AbstractAssembler::__construct()
     */
    public function __construct(seasar::container::ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * 手動で指定されたcomponentに対して、 プロパティ・インジェクションやセッターメソッド・インジェクションを実行します。
     *
     * @param object $component
     */
    public function assemble($component) {
        $componentDef = $this->getComponentDef();
        $beanDesc = seasar::beans::BeanDescFactory::getBeanDesc($componentDef->getComponentClass());
        $propDescs = $beanDesc->getTypehintPropertyDescs();
        $o = $componentDef->getPropertyDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $propertyDef  = $componentDef->getPropertyDef($i);
            $propertyName = $propertyDef->getPropertyName();
            $value = null;
            if ($beanDesc->hasPropertyDesc($propertyName)) {
                $propDesc = $beanDesc->getPropertyDesc($propertyName);
                try {
                    $value = $propertyDef->getValue();
                } catch(seasar::container::exception::TooManyRegistrationRuntimeException $e) {
                    if ($propDesc->isArrayAcceptable()) {
                        $childComponentDefs = $propertyDef->getChildComponentDef()->getComponentDefs();
                        $value = array();
                        foreach ($childComponentDefs as $childComponentDef) {
                           $value[] = $childComponentDef->getComponent();
                        }
                    } else {
                        throw $e;
                    }
                }
                $propDesc->setValue($component, $value);
            } else {
                throw new seasar::container::exception::PropertyNotFoundRuntimeException($componentDef->getComponentClass(), $propertyName);
            }
        }
    }
}