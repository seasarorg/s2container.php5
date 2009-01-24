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
 * S2Aop.PHP5 を用いてアスペクトを織り込んだクラスを生成し、そのオブジェクトを取得します。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.util
 * @author    klove
 */
namespace seasar\container\util;
class AopUtil {

    /**
     * AopUtilの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * アスペクトを織り込んだクラスを生成し、そのオブジェクトを返します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @param array $args
     * @return object
     */
    public static function getInstance(\seasar\container\ComponentDef $componentDef, $args) {
        $parameters = array();
        $parameters[\seasar\container\Config::COMPONENT_DEF_NAME] = $componentDef;
        return \seasar\aop\S2AopFactory::create($componentDef->getComponentClass(), self::getAspects($componentDef), $args, $parameters);
    }

    /**
     * ComponentDefに登録されているAspectDefを取得します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     * @return array
     */
    private static function getAspects(\seasar\container\ComponentDef $componentDef) {
        $aspectDefs = $componentDef->getAspectDefs();
        $aspects = array();
        foreach($aspectDefs as $aspectDef) {
            $aspects[] = $aspectDef->getAspect();
        }
        return $aspects;
    }
}
