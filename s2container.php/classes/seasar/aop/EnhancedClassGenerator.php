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
 * アスペクト対象クラスのEnhancedクラスを生成するGeneratorクラスです。
 * 
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
class EnhancedClassGenerator {
    /**
     * Enhancedクラスのプロパティやメソッド名に付加します。
     */
    const CLASS_NAME_POSTFIX = '_EnhancedByS2AOP';

    /**
     * アクセス修飾子をprivateとし、このクラスの生成を許可しません。
     */
    private function __construct() {}

    /**
     * Enhancedクラスのソースを生成し、eval関数でクラスを定義します。戻り値として、Enhancedクラス名を返します。
     *
     * @param ReflectionClass $targetClass Enhance対象のクラス
     * @param array $applicableMethods Enhance対象のクラスのメソッドのうち、アスペクト対象となるメソッドのReflectionの配列
     * @return string Enhancedクラス名
     */
    public static function generate(ReflectionClass $targetClass, array $applicableMethods) {
        $concreteClassName = $targetClass->getName() . self::CLASS_NAME_POSTFIX;
        if (class_exists($concreteClassName, false)) {
            return $concreteClassName;
        }

        if (true === seasar::aop::Config::$CACHING and
            true === self::loadCache($targetClass)) {
            return $concreteClassName;
        }

        $concreteClassSrc = self::generateInternal($concreteClassName, $targetClass, $applicableMethods);
        seasar::util::EvalUtil::execute($concreteClassSrc);

        if (true === seasar::aop::Config::$CACHING) {
            self::saveCache($targetClass, $concreteClassSrc);
        }
        return $concreteClassName;
    }

    /**
     * Enhancedクラスのソースを生成します。戻り値としてEnhancedクラスのソース文字列を返します。
     * @param string $concreteClassName Enhancedクラスの名前
     * @param ReflectionClass Enhance対象のクラス
     * @param array $applicableMethods Enhance対象のクラスのメソッドのうち、アスペクト対象となるメソッドのReflectionの配列
     * @return string Enhancedクラスのソース
     */
    public static function generateInternal($concreteClassName, ReflectionClass $targetClass, array $applicableMethods) {
        self::validateTargetClass($targetClass);
        $className = seasar::util::ClassUtil::getClassName($concreteClassName);
        $packageName = seasar::util::ClassUtil::getNamespace($concreteClassName);
        $srcLine = '';
        if ($packageName != '::') {
            $srcLine = 'namespace ' . $packageName . ';' . PHP_EOL;
        }
        $srcLine .= 'class ' . $className . ' ';
        if ($targetClass->isInterface()) {
            $srcLine .= 'implements ' . $targetClass->getName() . ' { ';
        } else {
            $srcLine .= 'extends ' . $targetClass->getName() . ' { ';
        }
        $srcLine .= PHP_EOL;
        $srcLine .= '    public $class_EnhancedByS2AOP = null;
    public $concreteClass_EnhancedByS2AOP = null;
    public $methodInterceptorsMap_EnhancedByS2AOP = array();
    public $parameters_EnhancedByS2AOP = null;' . PHP_EOL;
        $abstractMethods = array();
        foreach ($applicableMethods as $methodRef) {
            $methodDef = self::getMethodDefSrc($methodRef);
            if ($methodDef === false) {
                seasar::log::S2Logger::getLogger(__CLASS__)->info("cannot parse param [{$methodRef->getDeclaringClass()->getName()}::{$methodRef->getName()}()]",__METHOD__);
                continue;
            }
            if ($methodRef->isAbstract()) {
                $abstractMethods[] = $methodRef->getName();
            }

            $srcLine .= self::getMethodSrc($targetClass, $methodRef, $methodDef);
            $srcLine .= PHP_EOL;
        }
        $srcLine .= self::addInvokeParentMethodSrc($abstractMethods);
        $srcLine .= PHP_EOL;
        $srcLine .= '    private function __invokeMethodInvocationProceed_EnhancedByS2AOP() {
        $args = func_get_args();
        $methodName = array_pop($args);
        $methodInvocation = new seasar::aop::impl::S2MethodInvocationImpl($this,
            $this->class_EnhancedByS2AOP,
            $this->class_EnhancedByS2AOP->getMethod($methodName),
            $this->concreteClass_EnhancedByS2AOP,
            $args,
            $this->methodInterceptorsMap_EnhancedByS2AOP[$methodName],
            $this->parameters_EnhancedByS2AOP);
        return $methodInvocation->proceed();
    }' . PHP_EOL;
        $srcLine .= '}' . PHP_EOL;
        return $srcLine;
    }

    /**
     * メソッドの定義部分のソースを生成します。
     * @param ReflectionMethod $method
     * @return string
     */
    public static function getMethodDefSrc(ReflectionMethod $method) {
        $src = 'public function ' . $method->getName() . '(';
        $params = $method->getParameters();
        $paramSrcs = array();
        foreach($params as $param) {
            $paramSrc = '';
            if ($param->isArray()) {
                $paramSrc .= 'array ';
            } else if ($param->getClass() !== null) {
                $clazz = $param->getClass();
                $className = $clazz->getName();
                if (seasar::util::ClassUtil::isGlobalClass($clazz)) {
                    $className = '::' . $className;
                }
                $paramSrc .= $className . ' ';
            }
            if ($param->isPassedByReference()) {
                $paramSrc .= '&';
            }
            $paramSrc .= '$' . $param->getName();

            if ($param->isDefaultValueAvailable()) {
                $valSrc = '';
                $defaultValue = $param->getDefaultValue();
                if (is_string($defaultValue)) {
                    $valSrc .= "'$defaultValue'";
                } else if (is_array($defaultValue)) {
                    if (count($defaultValue) === 0) {
                        $valSrc .= 'array()';
                    } else {
                        return false;
                    }
                } else if (is_null($defaultValue)) {
                    $valSrc .= 'null';
                } else if (is_bool($defaultValue)) {
                    $valSrc .= $defaultValue ? 'true' : 'false';
                } else if (is_numeric($defaultValue)){
                    $valSrc .= $defaultValue;
                } else {
                    return false;
                }
                $paramSrc .= ' = ' . $valSrc;
            }
            $paramSrcs[] = $paramSrc;
        }
        return $src . implode(', ', $paramSrcs) . ') {';
    }

    /**
     * Enhance対象クラス(parent)のメソッドを呼び出すメソッドのソースを生成します。
     *
     * private $abstractMethods_EnhancedByS2AOP = array('findById', 'findAll');
     * private function __invokeParentMethod_EnhancedByS2AOP() {
     *     $args = func_get_args();
     *     $methodName = array_pop($args);
     *     if (in_array($methodName, $this->abstractMethods_EnhancedByS2AOP)) {
     *         throw new seasar::aop::exception::AbstractMethodInvocationRuntimeException(
     *                               $this->class_EnhancedByS2AOP->getName(), $methodName);
     *     }
     *     return call_user_func_array(array($this, 'parent::' . $methodName), $args);
     * }
     *
     * parentメソッドがabstarctメソッドの場合は、AbstractMethodInvocationRuntimeExceptionがスローされます。
     *
     * @param array $abstractMethods Enhance対象クラスのabstractメソッド名の配列
     * @return string
     */
    public static function addInvokeParentMethodSrc(array $abstractMethods) {
        $methods = implode('\',\'', $abstractMethods);
        $methodContent = '    private $abstractMethods_EnhancedByS2AOP = array(\'' . $methods . '\');
    public function __invokeParentMethod_EnhancedByS2AOP() {
        $args = func_get_args();
        $methodName = array_pop($args);
        if (in_array($methodName, $this->abstractMethods_EnhancedByS2AOP)) {
            throw new seasar::aop::exception::AbstractMethodInvocationRuntimeException($this->class_EnhancedByS2AOP->getName(), $methodName);
        }
        return call_user_func_array(array($this, \'parent::\' . $methodName), $args);
    }';
        return $methodContent . PHP_EOL;
    }

    /**
     * アスペクト対象となるメソッドに対して次のメソッドを生成します。
     * public function sample_EnhancedByS2AOP() {
     *     return $this->__invokeParentMethod_EnhancedByS2AOP('hoge');
     * }
     * public function sample() {
     *     if (array_key_exists('sample', $this->methodInterceptorsMap_EnhancedByS2AP)) {
     *         return $this->__invokeMethodInvocationProceed_EnhancedByS2AOP('sample');
     *     }
     *     return $this->__invokeParentMethod_EnhancedByS2AOP('sample');
     * }
     *
     * @param ReflectionClass Enhance対象のクラス
     * @param ReflectionMethod アスペクトを適用するメソッドのReflectionMethod
     * @param string $methodDef アスペクトを適用するメソッドのメソッド定義部分のソース
     * @return string メソッドのソース
     */
    public static function getMethodSrc(ReflectionClass $targetClass, ReflectionMethod $refMethod, $methodDef) {
        //$methodDef = str_ireplace('protected', 'public ', $methodDef);
        $params = $refMethod->getParameters();
        $args = array();
        foreach ($params as $param) {
            $args[] = '$' . $param->getName();
        }
        $args[] = '\'' . $refMethod->getName() . '\'';
        $args = implode(', ', $args);
        $parentMethodName = $refMethod->getName() . self::CLASS_NAME_POSTFIX;
        if ($targetClass->hasMethod($parentMethodName)) {
            throw new seasar::aop::exception::EnhancedClassGenerationRuntimeException($targetClass->getName(), (array)$parentMethodName);
        }
        $parentMethodDef  = str_replace($refMethod->getName(), $parentMethodName, $methodDef) . PHP_EOL;
        $parentMethodContent ='        return $this->__invokeParentMethod_EnhancedByS2AOP(' . $args . ');' . PHP_EOL;

        $methodContent = '    ' . $methodDef;
        $methodContent .= '
        if (array_key_exists(\'@@METHOD_NAME@@\', $this->methodInterceptorsMap_EnhancedByS2AOP)) {
            return $this->__invokeMethodInvocationProceed_EnhancedByS2AOP(' . $args . ');
        }
        return $this->__invokeParentMethod_EnhancedByS2AOP(' . $args . ');
    }' . PHP_EOL;
        $methodContent  = str_replace('@@METHOD_NAME@@', $refMethod->getName(), $methodContent);
        return $methodContent;
    }

    /**
     * Enhancedクラスのソースをキャッシュとして保存するファイル名を返します。
     *
     * @param ReflectionClass $targetClass
     * @return string
     */
    protected static function getCacheFile(ReflectionClass $targetClass) {
        return seasar::aop::Config::$CACHE_DIR . DIRECTORY_SEPARATOR
             . sha1($targetClass->getFileName() . '$$' . $targetClass->getName()) . '.php';
    }

    /**
     * Enhancedクラスのキャッシュファイルが存在すればrequireします。
     * キャッシュファイルのタイムスタンプが対象クラスのファイルより古い場合は使用しません。
     * キャッシュファイルが再作成されます。
     *
     * @param ReflectionClass $targetClass
     * @return boolean
     */
    protected static function loadCache(ReflectionClass $targetClass) {
        $cacheFile = self::getCacheFile($targetClass);
        if (is_file($cacheFile)) {
            if (filemtime($cacheFile) > filemtime($targetClass->getFileName())) {
                require_once($cacheFile);
                seasar::log::S2Logger::getInstance()->debug('load aop cache of ' . $targetClass->getName(), __METHOD__);
                return true;
            } else {
                seasar::log::S2Logger::getInstance()->debug('old aop cache of ' . $targetClass->getName() . ' found. regenerate.', __METHOD__);
            }
        } else {
            seasar::log::S2Logger::getInstance()->debug('aop cache of ' . $targetClass->getName() . ' not found.', __METHOD__);
        }
        return false;
    }

    /**
     * Enhancedクラスのソースをキャッシュとして保存します。
     *
     * @param ReflectionClass $targetClass
     * @param string $concreteClassSrc
     * @throw seasar::aop::exception::CacheDirectoryUnwritableException
     */
    protected static function saveCache(ReflectionClass $targetClass, $concreteClassSrc) {
        $cacheFile = self::getCacheFile($targetClass);
        if (is_writeable(seasar::aop::Config::$CACHE_DIR)) {
            seasar::log::S2Logger::getInstance()->debug('write aop cache of ' . $targetClass->getName(), __METHOD__);
            file_put_contents($cacheFile, '<?php' . PHP_EOL . $concreteClassSrc, LOCK_EX);
        } else {
            throw new seasar::aop::exception::CacheDirectoryUnwritableException(seasar::aop::Config::$CACHE_DIR);
        }
    }

    /**
     * Enhanceする対象クラスがS2Aop.PHPが使用するプロパティやメソッドを実装していないことを確認します。
     *
     * @param ReflectionClass $targetClass
     * @throw seasar::aop::exception::EnhancedClassGenerationRuntimeException
     */
    private static function validateTargetClass(ReflectionClass $targetClass) {
        if ($targetClass->hasProperty('class_EnhancedByS2AOP') or
            $targetClass->hasProperty('concreteClass_EnhancedByS2AOP') or
            $targetClass->hasProperty('methodInterceptorsMap_EnhancedByS2AOP') or
            $targetClass->hasProperty('parameters_EnhancedByS2AOP') or
            $targetClass->hasMethod('__invokeParentMethod_EnhancedByS2AOP') or
            $targetClass->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP')) {
            throw new seasar::aop::exception::EnhancedClassGenerationRuntimeException($targetClass->getName(), array('clazz_EnhancedByS2AOP', 'concreteClass_EnhancedByS2AOP', 'methodInterceptorsMap_EnhancedByS2AOP', 'parameters_EnhancedByS2AOP'));
        }
    }
}
