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
 * Aspectを織り込んだクラスを作成し、そのインスタンスを生成するファクトリクラスです。
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
class S2AopFactory {

    /**
     * S2AopFactoryクラスの構築は許可されていません。
     */
    private function __construct() {}

    /**
     * Aspectを織り込んだクラスを作成し、そのインスタンスを生成します。
     * @param ReflectionClass $targetClass Aspect対象クラス
     * @param array $aspects 織り込むAspect
     * @param array $args Aspectを織り込んだクラスのコンストラクタ引数
     * @param array $parameters S2Aop.PHP用の拡張パラメータ
     * @return object
     */
    public static function create(ReflectionClass $targetClass, array $aspects, array $args = array(), array $parameters = array()) {
        if (!$targetClass->isUserDefined()) {
            seasar::log::S2Logger::getLogger(__CLASS__)->info("cannot aspect to not user defined class [{$targetClass->getName()}].",__METHOD__);
            return seasar::util::ClassUtil::newInstance($targetClass, $args);
        }

        if ($targetClass->isFinal()) {
            seasar::log::S2Logger::getLogger(__CLASS__)->info("cannot aspect to final class [{$targetClass->getName()}].",__METHOD__);
            return seasar::util::ClassUtil::newInstance($targetClass, $args);
        }

        $applicableMethods = self::getApplicableMethods($targetClass);
        $methodInterceptorsMap = self::creatMethodInterceptorsMap($targetClass, $aspects, $applicableMethods);
        $generator = Config::$ENHANCED_CLASS_GENERATOR;
        $enhancedClassName = $generator::generate($targetClass, $applicableMethods);

        $enhancedClassRef = new ReflectionClass($enhancedClassName);
        $targetObj = seasar::util::ClassUtil::newInstance($enhancedClassRef, $args);
        $targetObj->class_EnhancedByS2AOP                 = $targetClass;
        $targetObj->concreteClass_EnhancedByS2AOP         = $enhancedClassRef;
        $targetObj->methodInterceptorsMap_EnhancedByS2AOP = $methodInterceptorsMap;
        $targetObj->parameters_EnhancedByS2AOP            = $parameters;
        return $targetObj;
    }

    /**
     * アスペクト対象のクラスの各メソッドについて、適用するアスペクトのマップを作成します。
     * @param ReflectionClass $targetClass アスペクト対象クラス
     * @param array $aspects 織り込むAspect
     * @param array $applicableMethods アスペクト適用可能なメソッド群
     * @return array
     */
    private static function creatMethodInterceptorsMap(ReflectionClass $targetClass, $aspects = null, $applicableMethods = array()) {
        $methodInterceptorsMap = array();
        foreach ($applicableMethods as $method) {
            $methodName = $method->getName();
            $interceptorList = array();
            foreach($aspects as $aspect) {
                if ($aspect->getPointcut()->isApplied($methodName)) {
                    $interceptorList[] = $aspect->getMethodInterceptor();
                }
            }

            if (count($interceptorList) > 0) {
                $methodInterceptorsMap[$methodName] = $interceptorList;
            }
        }
        return $methodInterceptorsMap;
    }

    /**
     * アスペクト対象クラスのメソッドについて、アスペクト可能なメソッドを抽出します。
     * @param ReflectionClass $targetClass アスペクト対象クラス
     * @return array
     */
    public static function getApplicableMethods(ReflectionClass $targetClass) {
        $methods = $targetClass->getMethods();
        $applicableMethods = array();
        foreach($methods as $method) {
            if (!$method->isPublic() or
                0 === strpos($method->getName(), '_') or
                $method->isStatic() or
                $method->isFinal() or
                $method->isConstructor() ) {
                    continue;
            }
            $applicableMethods[] = $method;
        }
        return $applicableMethods;
    }
}
