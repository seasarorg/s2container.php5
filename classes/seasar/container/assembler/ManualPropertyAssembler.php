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
 * 手動で設定されたものだけを対象にするプロパティアセンブラです。
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
class ManualPropertyAssembler {

    /**
     * @var \seasar\container\ComponentDef
     */
    protected $componentDef;

    /**
     * @param \seasar\container\ComponentDef $componentDef
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        $this->componentDef = $componentDef;
    }

    /**
     * 手動で指定されたcomponentに対して、 プロパティ・インジェクションやセッターメソッド・インジェクションを実行します。
     *
     * @param object $component
     */
    public function assemble($component) {
        $beanDesc = \seasar\beans\BeanDescFactory::getBeanDesc($this->componentDef->getComponentClass());
        $propDescs = $beanDesc->getTypehintPropertyDescs();
        $propertyDefs = $this->componentDef->getPropertyDefs();
        foreach ($propertyDefs as $propertyName => $propertyDef) {
            $value = null;
            if ($beanDesc->hasPropertyDesc($propertyName)) {
                $propDesc = $beanDesc->getPropertyDesc($propertyName);
                try {
                    $value = $propertyDef->getValue();
                    if ($propDesc->isArrayAcceptable() and !is_array($value)) {
                        $value = array($value);
                    }
                } catch(\seasar\container\exception\TooManyRegistrationRuntimeException $e) {
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
                throw new \seasar\exception\PropertyNotFoundRuntimeException($this->componentDef->getComponentClass(), $propertyName);
            }
        }
    }
}
