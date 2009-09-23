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
 * ConstructClosureを用いてオブジェクトを生成します。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   seasar.container.assembler
 * @author    klove
 */
namespace seasar\container\assembler;
class ConstructClosureAssembler extends AbstractAssembler {
    /**
     * @return object
     */
    public function assemble() {
        $componentDef = $this->getComponentDef();
        if ($componentDef->hasConstructClosure()) {
            $componentClass = $componentDef->getComponentClass();
            $closure = $componentDef->getConstructClosure();
            $component = $closure($componentDef);
            if (is_object($component) && $componentClass->isInstance($component)) {
                return $component;
            } else {
                if (is_object($component)) { 
                    $realClassName = get_class($component);
                } else if(is_null($component)) {
                    $realClassName = 'null';
                } else {
                    $realClassName = strval($component);
                }
                throw new \seasar\container\exception\ClassUnmatchRuntimeException($componentClass->getName(), $realClassName);
            }
        } else {
            return $componentDef->getComponentClass()->newInstance();
        }
    }
}
