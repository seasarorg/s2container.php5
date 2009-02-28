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
 * S2ApplicationContext は、ファイルシステムからクラス定義ファイルを検索し、
 * 見つかったクラスをコンポーネントとして持つS2Containerを生成するユーティリティクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.2.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
class S2ApplicationContext {
    /**
     * @var array
     */
    public static $CLASSES = array();

    /**
     * @var array
     */
    public static $DICONS = array();

    /**
     * @var array
     */
    public static $COMPONENT_INFOS = array();

    /**
     * @var array
     */
    public static $includeDiconPatterns = array();

    /**
     * @var array
     */
    public static $excludeDiconPatterns = array();

    /**
     * @var array
     */
    public static $includeClassPatterns = array();

    /**
     * @var array
     */
    public static $excludeClassPatterns = array();

    /**
     * @var array
     */
    public static $autoAspects = array();

    /**
     * @var boolean
     */
    public static $READ_PARENT_ANNOTATION = false;

    /**
     * @var array
     */
    public static $SINGLETON_CONTAINERS = array();

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
     * 初期化処理を行います。
     */
    public static function init() {
        self::$CLASSES = array();
        self::$DICONS  = array();
        self::$COMPONENT_INFOS = array();
        self::$SINGLETON_CONTAINERS = array();
        self::$includeDiconPatterns = array();
        self::$excludeDiconPatterns = array();
        self::$includeClassPatterns = array();
        self::$excludeClassPatterns = array();
        self::$autoAspects = array();
    }

    /**
     * 各メソッドに付いているコメントアノテーションについて、親クラスのアノテーションも読むかどうかを設定します。
     *
     * @param boolean $val
     */
    public static function setReadParentAnnotation($val = true) {
        self::$READ_PARENT_ANNOTATION = $val;
    }

    /**
     * @see seasar\container\S2ApplicationContext::register()
     */
    public static function add($info) {
        return self::register($info);
    }

    /**
     * コンポーネント情報を登録します。
     * s2component関数から呼ばれます。
     *
     * @param seasar\container\ComponentInfoDef|string $info
     * @return seasar\container\ComponentInfoDef
     */
    public static function register($info) {
        if (!($info instanceof seasar\container\ComponentInfoDef)) {
            $info = new \seasar\container\ComponentInfoDef($info);
        }
        self::$COMPONENT_INFOS[] = $info;
        return $info;
    }

    /**
     * 自動アスペクト情報を登録します。
     *
     * @param string $interceptor
     * @param string $componentPattern
     * @param string $pointcut
     * @return seasar\container\AspectInfoDef
     */
    public static function registerAspect($interceptor, $componentPattern = null, $pointcut = null) {
        if ($interceptor instanceof \seasar\container\AspectInfoDef) {
            $info = $interceptor;
        } else {
            $info = new \seasar\container\AspectInfoDef($interceptor, $componentPattern, $pointcut);
        }
        self::$autoAspects[] = $info;
        return $info;
    }

    /**
     * ファイルシステムからクラス定義ファイルとダイコンファイルを検索します。
     *
     * @param string $path
     * @param string|array $namespace
     * @param boolean $strict
     *                trueの場合、$namespaceで指定されたネームスペースが使用されます。
     *                falseの場合は、検索したサブディレクトリが$namespaceに順次追加されます。
     * @param boolean $pear
     *                trueの場合は、$namespaceが「_」で展開されます。
     *                falseの場合は、$namespaceが「::」で展開されます。
     * @param boolean $recursive
     *                trueの場合は、再起的にディレクトリを検索します。
     *                falseの場合は、サブディレクトリを検索しません。
     */
    public static function import($path, $namespace = array(), $strict = false, $pear = false, $recursive = true) {
        if (is_string($namespace)) {
            if ($pear) {
                $namespace = explode('_', $namespace);
            } else {
                $namespace = explode('\\', $namespace);
            }
        }

        if (is_dir($path)) {
            self::scanDir($path, $namespace, $strict, $pear, $recursive);
        } else if (is_file($path)) {
            self::importInternal($path, $namespace, $pear);
        } else if (class_exists($path) or interface_exists($path)) {
            self::$CLASSES[$path] = null;
        } else {
            trigger_error(__CLASS__ . " : invalid args. [$path]", E_USER_WARNING);
        }
    }

    /**
     * ファイルシステムを再帰的に検索します。
     *
     * @see \seasar\container\S2ApplicationContext::import()
     */
    private static function scanDir($parentPath, $namespace, $strict, $pear, $recursive) {
        $iterator = new \DirectoryIterator($parentPath);
        while($iterator->valid()) {
            if (strpos($iterator->getFilename(), '.') === 0) {
                $iterator->next();
                continue;
            }
            if ($iterator->isFile()) {
                self::importInternal($iterator->getRealPath(), $namespace, $pear);
            } else if ($recursive and $iterator->isDir()) {
                if ($strict) {
                    self::scanDir($iterator->getRealPath(), $namespace, $strict, $pear, $recursive);
                } else {
                    self::scanDir($iterator->getRealPath(), array_merge($namespace, (array)$iterator->getFileName()), $strict, $pear, $recursive);
                }
            }
            $iterator->next();
        }
    }

    /**
     * ファイルシステムから検索されたクラス定義ファイルとダイコンファイルを取得します。
     *
     * @param string $filePath
     * @param array $namespace
     * @param boolean $pear
     */
    public static function importInternal($filePath, array $namespace = array(), $pear = false){
        $fileName = basename($filePath);
        $fileNameRev = strrev($fileName);
        if (stripos($fileNameRev, 'php.') === 0) {
            $namespace[] = substr($fileName, 0, strpos($fileName, '.'));
            if ($pear) {
                $className = implode('_' , $namespace);
            } else {
                $className = implode('\\' , $namespace);
            }
            self::$CLASSES[$className] = $filePath;
            \seasar\util\ClassLoader::$CLASSES[$className] = $filePath;
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("find class $className : $filePath", __METHOD__);
        } else if (stripos($fileNameRev, 'nocid.') === 0) {
            self::$DICONS[$fileName] = $filePath;
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("find dicon $fileName : $filePath", __METHOD__);
        } else {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("ignore file $fileName : $filePath", __METHOD__);
        }
    }

    /**
     * コンテナを生成してコンポーネントを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string  $arg1
     * @param string  $arg2
     * @return object
     */
    public static function getComponent($arg1, $arg2 = null) {
        return self::getComponentDef($arg1, $arg2)->getComponent();
    }

    /**
     * @see \seasar\container\S2ApplicationContext::getComponent()
     */
    public static function get($arg1, $arg2 = null) {
        return self::getComponent($arg1, $arg2);
    }

    /**
     * コンテナを生成して、ComponentDefを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string  $arg1
     * @param string  $arg2
     * @return \seasar\container\ComponentDef
     */
    public static function getComponentDef($arg1, $arg2 = null) {
        if (is_null($arg2)) {
            $namespace = '';
            $key = $arg1;
        } else {
            $namespace = arg1;
            $key = $arg2;
        }
        if (!array_key_exists($namespace, self::$SINGLETON_CONTAINERS)) {
            self::$SINGLETON_CONTAINERS[$namespace] = self::create($namespace);
        }
        return self::$SINGLETON_CONTAINERS[$namespace]->getComponentDef($key);
    }

    /**
     * コンテナを生成して、ComponentDefが存在するかどうかを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string  $arg1
     * @param string  $arg2
     * @return boolean
     */
    public static function hasComponentDef($arg1, $arg2 = null) {
        if (is_null($arg2)) {
            $namespace = '';
            $key = $arg1;
        } else {
            $namespace = arg1;
            $key = $arg2;
        }
        if (!array_key_exists($namespace, self::$SINGLETON_CONTAINERS)) {
            self::$SINGLETON_CONTAINERS[$namespace] = self::create($namespace);
        }
        return self::$SINGLETON_CONTAINERS[$namespace]->hasComponentDef($key);
    }

    /**
     * importされたクラスとダイコンファイルからS2Containerを生成します。
     *
     * @param string $namespace
     * @return \seasar\container\S2Container
     */
    public static function create($namespace = '') {
        $dicons = array_values(self::$DICONS);
        if (count($dicons) > 0) {
            $dicons = self::includeFilter($dicons, self::$includeDiconPatterns);
            $dicons = self::excludeFilter($dicons, self::$excludeDiconPatterns);
        }
        $classes = array_keys(self::$CLASSES);
        if (count($classes) > 0) {
            $classes = self::includeFilter($classes, self::$includeClassPatterns);
            $classes = self::excludeFilter($classes, self::$excludeClassPatterns);
        }

        $infos = self::$COMPONENT_INFOS;
        foreach ($classes as $className) {
            $info = self::createComponentInfoDef($className);
            if (!is_null($info)) {  // if available of @S2Component annotation is flase
                $infos[] = $info;
            }
        }

        if (count($dicons) == 0 and count($infos) == 0) {
            \seasar\log\S2Logger::getLogger(__CLASS__)->info("dicon, class not found at all. create empty container.", __METHOD__);
            return new \seasar\container\impl\S2ContainerImpl();
        }
        $container = self::createInternal($dicons, $infos, $namespace);
        return $container;
    }

    /**
     * クラスに対するComponentInfoDefを生成します。
     * コンポーネント情報はコメントアノテーションで取得します。
     *
     * @param string $className
     * @return \seasar\container\ComponentInfoDef
     */
    public static function createComponentInfoDef($className) {
        $refClass = new \ReflectionClass($className);
        if (!\seasar\util\Annotation::has($refClass, self::COMPONENT_ANNOTATION)) {
            return new \seasar\container\ComponentInfoDef($refClass);
        }

        $componentInfo = \seasar\util\Annotation::get($refClass, self::COMPONENT_ANNOTATION);
        if (isset($componentInfo['available']) and (boolean)$componentInfo['available'] === false) {
            return null;
        }

        $info = new \seasar\container\ComponentInfoDef($refClass);
        if (isset($componentInfo['name'])) {
            $info->setName($componentInfo['name']);
        }

        if (isset($componentInfo['instance'])) {
            $info->setInstance($componentInfo['instance']);
        }

        if (isset($componentInfo['autoBinding'])) {
            $info->setAutoBinding($componentInfo['autoBinding']);
        }

        if (isset($componentInfo['namespace'])) {
            $info->setNamespace($componentInfo['namespace']);
        }

        return $info;
    }

    /**
     * 指定されたダイコンファイルとクラスからS2Containerを生成します。
     *   - rootのS2Containerを生成します。
     *   - 各ダイコンファイルからS2Containrを生成し、rootのコンテナに子コンテナとして登録します。
     *   - 指定された各クラスに対するComponentDefを全て生成しrootコンテナに登録します。
     *   - 各ComponentDefのセットアップを実施します。
     *
     * @param array $dicons
     * @param array $infos
     * @return \seasar\container\S2Container
     */
    public static function createInternal($dicons, $infos, $namespaceArg) {
        $container = new \seasar\container\impl\S2ContainerImpl();
        foreach ($dicons as $dicon) {
            $child = \seasar\container\factory\S2ContainerFactory::includeChild($container, $dicon);
            $child->setRoot($container->getRoot());
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("include dicon : $dicon", __METHOD__);
        }

        $registeredComponentDefs = array();
        foreach($infos as $info) {
            $namespace = self::namespaceFilter($info->getNamespace(), $namespaceArg);
            if ($namespace === false) {
                continue;
            }
            $cd = self::createComponentDef($info);
            $registeredComponentDefs[] = $cd;
            self::registerComponentDef($container, $cd, $namespace);
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug('import component : ' . $info->getClassName(), __METHOD__);
        }

        foreach($registeredComponentDefs as $cd) {
            self::setupComponentDef($cd);
        }

        return $container;
    }

    /**
     * コンポーネントが特定のネームスペース配下に存在した場合は、特定のネームスペースを除くネームスペースを返す。
     * 特定のネームスペースが a.b 、コンポーネントのネームスペースが a.b.c の場合、c を返す。
     * コンポーネントのネームスペースが、a.b の場合は空文字列を返す。
     * コンポーネントのネームスペースが、a.b で始まらない場合は、booleanのfalseを返す。
     *
     * @param string $namespace コンポーネントのネームスペース
     * @param string $namespaceArg 特定のネームスペース
     * @return boolean|string
     */
    public static function namespaceFilter($namespace, $namespaceArg) {
        if ($namespaceArg == '') {
            return $namespace;
        }

        if ($namespace === $namespaceArg) {
            return '';
        }

        if (strpos($namespace, $namespaceArg . '.') === 0) {
            return substr($namespace, strlen($namespaceArg) + 1);
        }

        \seasar\log\S2Logger::getLogger(__CLASS__)->debug("ignored by namespace : $namespace not in $namespaceArg", __METHOD__);
        return false;
    }

    /**
     * クラスに対するComponentDefを生成します。
     * コンポーネント情報はコメントアノテーションで取得します。
     *
     * @param seasar\container\ComponentInfoDef $info
     * @return \seasar\container\ComponentDef
     */
    public static function createComponentDef($info) {
        if ($info->hasName()) {
            $cd = new \seasar\container\impl\ComponentDefImpl($info->getReflectionClass(), $info->getName());
        } else {
            $cd = new \seasar\container\impl\ComponentDefImpl($info->getReflectionClass());
        }
        if ($info->hasInstance()) {
            $cd->setInstanceDef(\seasar\container\deployer\InstanceDefFactory::getInstanceDef($info->getInstance()));
        }
        if ($info->hasAutoBinding()) {
            $cd->setAutoBindingDef(\seasar\container\assembler\AutoBindingDefFactory::getAutoBindingDef($info->getAutoBinding()));
        }
        return $cd;
    }

    /**
     * 渡されたnamespaceをドットで区切って、それぞれについてコンテナを取得または生成します。
     * namespaceが空文字の場合に、渡されたコンテナにコンポーネントを登録します。
     *
     * @param \seasar\container\S2Container $container
     * @param \seasar\container\ComponentDef $cd
     * @param string|null $namespace
     */
    public static function registerComponentDef(\seasar\container\S2Container $container, \seasar\container\ComponentDef $cd, $namespace = '') {
        if ($namespace == '') { // 文字列一致、またはnull一致
            $container->register($cd);
        } else {
            $names = preg_split('/\./', $namespace, 2);
            if ($container->hasComponentDef($names[0])) {
                $childContainer = $container->getComponent($names[0]);
                if (!$childContainer instanceof \seasar\container\S2Container) {
                    throw new \seasar\container\exception\TooManyRegistrationRuntimeException($names[0], array($container->getComponentDef($names[0])->getComponentClass(), new \ReflectionClass('\seasar\container\impl\S2ContainerImpl')));
                }
            } else {
                $childContainer = new \seasar\container\impl\S2ContainerImpl();
                $childContainer->setNamespace($names[0]);
                $container->includeChild($childContainer);
            }
            $restNamespace = '';
            if (count($names) == 2) {
                $restNamespace = $names[1];
            }
            self::registerComponentDef($childContainer, $cd, $restNamespace);
        }
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

        foreach(self::$autoAspects as $aspectInfo) {
            if ($aspectInfo->applicable($cd)) {
                self::setupAspectDef($cd, $aspectInfo->toAnnotationArray());
            }
        }

        return $cd;
    }

    /**
     * PropertyDefをセットアップします。
     *
     * @param \seasar\container\ComponentDef $cd
     * @param \ReflectionClass $reflection
     * @param string $propName
     */
    private static function setupPropertyDef(ComponentDef $cd, $reflection, $propName) {
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
    private static function setupClassAspectDef(ComponentDef $cd, \ReflectionClass $classRef) {
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
    private static function setupMethodAspectDef(ComponentDef $cd, \ReflectionMethod $methodRef) {
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
    private static function setupAspectDef(ComponentDef $cd, array $annoInfo) {
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
    private static function setupClassMetaDef(ComponentDef $cd, \ReflectionClass $classRef) {
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

    /**
     * includeパターンによってフィルタします。
     *
     * @param array $items
     * @param array $patterns
     * @return array
     */
    public static function includeFilter($items, $patterns) {
        if (count($patterns) == 0) {
            return $items;
        }
        $results = array();
        foreach ($items as $item) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $results[] = $item;
                    break;
                }
            }
        }
        return $results;
    }

    /**
     * excludeパターンによってフィルタします。
     *
     * @param array $items
     * @param array $patterns
     * @return array
     */
    public static function excludeFilter($items, $patterns) {
        if (count($patterns) == 0) {
            return $items;
        }
        $results = array();
        foreach ($items as $item) {
            $exclude = false;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $item)) {
                    $exclude = true;
                    break;
                }
            }
            if ($exclude == false) {
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * パターンをセットします。
     *
     * @param string|array $pattern
     * @param boolean $condition
     */
    public static function setPattern($pattern, $condition = true) {
        if ($condition) {
            self::setIncludePattern($pattern);
        } else {
            self::setExcludePattern($pattern);
        }
    }

    /**
     * パターンを追加します。
     *
     * @param string $pattern
     * @param boolean $condition
     */
    public static function addPattern($pattern, $condition = true) {
        if ($condition) {
            self::addIncludePattern($pattern);
        } else {
            self::addExcludePattern($pattern);
        }
    }

    /**
     * includeパターンをセットします。
     *
     * @param string|array $pattern
     */
    public static function setIncludePattern($pattern = array()) {
        self::$includeDiconPatterns = (array)$pattern;
        self::$includeClassPatterns = (array)$pattern;
    }

    /**
     * includeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addIncludePattern($pattern) {
        self::$includeDiconPatterns[] = $pattern;
        self::$includeClassPatterns[] = $pattern;
    }

    /**
     * excludeパターンをセットします。
     *
     * @param string $pattern
     */
    public static function setExcludePattern($pattern = array()) {
        self::$excludeDiconPatterns = (array)$pattern;
        self::$excludeClassPatterns = (array)$pattern;
    }

    /**
     * excludeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addExcludePattern($pattern) {
        self::$excludeDiconPatterns[] = $pattern;
        self::$excludeClassPatterns[] = $pattern;
    }

}
