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
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.1
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar\container\factory;
class ComponentDefBuilder {

    /**
     * @var boolean
     */
    public static $READ_PARENT_ANNOTATION = false;

    /**
     * コンポーネント定義を行うコメントアノテーションです。
     */
    const COMPONENT_ANNOTATION = '@S2Component';

    /**
     * 自動バインディングを行うコメントアノテーションです。
     */
    const BINDING_ANNOTATION = '@S2Binding';

    /**
     * アスペクトを行うコメントアノテーションです。
     */
    const ASPECT_ANNOTATION = '@S2Aspect';

    /**
     * メタ情報を設定するコメントアノテーションです。
     */
    const META_ANNOTATION = '@S2Meta';

    /**
     *
     * @param ReflectionClass $refClass
     * @return \seasar\container\ComponentDef
     */
    public static function create(\seasar\container\S2Container $container, \ReflectionClass $refClass) {
        $cd = self::createComponentDef($refClass);
        if ($cd instanceof \seasar\container\ComponentDef) {
            $cd->setContainer($container);
            self::setupComponentDef($cd);
        }
        return $cd;
    }

    /**
     * クラスに対するComponentDefを生成します。
     * コンポーネント情報はコメントアノテーションで取得します。
     *
     * @param ReflectionClass $refClass
     * @return \seasar\container\ComponentDef
     */
    public static function createComponentDef($refClass) {
        if (!\seasar\util\Annotation::has($refClass, self::COMPONENT_ANNOTATION)) {
            return new \seasar\container\impl\ComponentDefImpl($refClass);
        }

        $componentInfo = \seasar\util\Annotation::get($refClass, self::COMPONENT_ANNOTATION);
        if (isset($componentInfo['available']) and (boolean)$componentInfo['available'] === false) {
            return null;
        }

        if (isset($componentInfo['name'])) {
            $cd = new \seasar\container\impl\ComponentDefImpl($refClass, $componentInfo['name']);
        } else {
            $cd = new \seasar\container\impl\ComponentDefImpl($refClass);
        }

        if (isset($componentInfo['instance'])) {
            $cd->setInstanceDef(\seasar\container\deployer\InstanceDefFactory::getInstanceDef($componentInfo['instance']));
        }

        if (isset($componentInfo['autoBinding'])) {
            $cd->setAutoBindingDef(\seasar\container\assembler\AutoBindingDefFactory::getAutoBindingDef($componentInfo['autoBinding']));
        }
        return $cd;
    }

    /**
     * ComponentDefをセットアップします。
     * コンポーネント情報はコメントアノテーションで取得します。
     *   - public プロパティに対するPropertyDefをセットアップします。
     *   - セッターメソッドに対するPropertyDefをセットアップします。
     *   - 各publicメソッドについてAspectDefをセットアップします。
     *   - クラスについてAspectDefをセットアップします。
     *   - クラスについてMetaDefをセットアップします。
     *   - 自動アスペクトのセットアップを行います。
     *
     * @param \seasar\container\ComponentDef $cd
     * @return \seasar\container\ComponentDef
     */
    public static function setupComponentDef(\seasar\container\ComponentDef $cd) {
        $classRef = $cd->getComponentClass();
        $beanDesc = \seasar\beans\BeanDescFactory::getBeanDesc($classRef);
        $propDescs = $beanDesc->getPropertyDescs();
        foreach ($propDescs as $propDesc) {
            $ref = $propDesc->getReflection();
            if (self::$READ_PARENT_ANNOTATION === false and 
                $ref->getDeclaringClass()->getName() !== $classRef->getName()) {
                continue;
            }
            if (\seasar\util\Annotation::has($ref, self::BINDING_ANNOTATION)) {
                self::setupPropertyDef($cd, $ref, $propDesc->getPropertyName());
            }
        }

        $methodRefs = $classRef->getMethods();
        foreach ($methodRefs as $methodRef) {
            if (self::$READ_PARENT_ANNOTATION === false and 
                $methodRef->getDeclaringClass()->getName() !== $classRef->getName()) {
                continue;
            }
            if (!$methodRef->isPublic() or 
                $methodRef->isConstructor() or
                strpos($methodRef->getName(), '_') === 0 ) {
                continue;
            }
            if (!$methodRef->isStatic() and 
                !$methodRef->isFinal() and
                \seasar\util\Annotation::has($methodRef, self::ASPECT_ANNOTATION)) {
                self::setupMethodAspectDef($cd, $methodRef);
            }
        }
        if (!$classRef->isFinal() and
            \seasar\util\Annotation::has($classRef, self::ASPECT_ANNOTATION)) {
            self::setupClassAspectDef($cd, $classRef);
        }

        if (\seasar\util\Annotation::has($cd->getComponentClass(), self::META_ANNOTATION)) {
            self::setupClassMetaDef($cd, $classRef);
        }
    }

    /**
     * PropertyDefをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param \ReflectionClass $reflection
     * @param string $propName
     */
    public static function setupPropertyDef(\seasar\container\ComponentDef $cd, $reflection, $propName) {
        $propInfo = \seasar\util\Annotation::get($reflection, self::BINDING_ANNOTATION);
        if (isset($propInfo[0])) {
            $propertyDef = new \seasar\container\impl\PropertyDef($propName);
            $cd->addPropertyDef($propertyDef);
            if ($cd->getContainer()->hasComponentDef($propInfo[0])) {
                $propertyDef->setChildComponentDef($cd->getContainer()->getComponentDef($propInfo[0]));
            } else {
                $propertyDef->setExpression($propInfo[0]);
            }
        } else {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("binding annotation found. cannot get values.", __METHOD__);
        }
    }

    /**
     * クラスに指定されているAspectをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param \ReflectionClass $reflection
     */
    public static function setupClassAspectDef(\seasar\container\ComponentDef $cd, \ReflectionClass $classRef) {
        $annoInfo = \seasar\util\Annotation::get($classRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        if (array_key_exists(0, $annoInfo)) {
            $annoInfo['interceptor'] = $annoInfo[0];
        }
        self::setupAspectDef($cd, $annoInfo);
    }

    /**
     * メソッドに指定されているAspectをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param \ReflectionMethod $methodRef
     */
    public static function setupMethodAspectDef(\seasar\container\ComponentDef $cd, \ReflectionMethod $methodRef) {
        $annoInfo = \seasar\util\Annotation::get($methodRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("method aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        if (array_key_exists(0, $annoInfo)) {
            $annoInfo['interceptor'] = $annoInfo[0];
        }
        $annoInfo['pointcut'] = '/^' . $methodRef->getName() . '$/';
        self::setupAspectDef($cd, $annoInfo);
    }

    /**
     * AspectDefをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param array $annoInfo
     */
    public static function setupAspectDef(\seasar\container\ComponentDef $cd, array $annoInfo) {
        if (isset($annoInfo['interceptor'])) {
            if (isset($annoInfo['pointcut']) and is_string($annoInfo['pointcut'])) {
                $pointcut = new \seasar\aop\Pointcut($annoInfo['pointcut']);
            } else {
                $pointcut = new \seasar\aop\Pointcut($cd->getComponentClass());
            }
            $aspectDef = new \seasar\container\impl\AspectDef($pointcut);
            $cd->addAspectDef($aspectDef);
            if ($cd->getContainer()->hasComponentDef($annoInfo['interceptor'])) {
                $aspectDef->setChildComponentDef($cd->getContainer()->getComponentDef($annoInfo['interceptor']));
            } else {
                $aspectDef->setExpression($annoInfo['interceptor']);
            }
        } else {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("invalid aspect info. cannot get interceptor value.", __METHOD__);
        }
    }

    /**
     * MetaDefをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param \ReflectionClass $classRef
     */
    public static function setupClassMetaDef(\seasar\container\ComponentDef $cd, \ReflectionClass $classRef) {
        $annoInfo = \seasar\util\Annotation::get($classRef, self::META_ANNOTATION);
        if (count($annoInfo) === 0) {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        foreach($annoInfo as $key => $val) {
            $metaDef = new \seasar\container\impl\MetaDef($key);
            $cd->addMetaDef($metaDef);
            $metaDef->setExpression($val);
            if ($cd->getContainer()->hasComponentDef($val)) {
                $metaDef->setChildComponentDef($cd->getContainer()->getComponentDef($val));
            } else {
                $metaDef->setExpression($val);
            }
        }
    }
}
