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
 * S2ApplicationContext は、ファイルシステムからクラス定義ファイルを検索し、
 * 見つかったクラスをコンポーネントとして持つS2Containerを生成するユーティリティクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.2.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar::container;
class S2ApplicationContext {
    /**
     * @var array
     */
    public static $CLASSES = array();

    /**
     * @var array
     */
    public static $DICONS  = array();

    /**
     * @var array
     */
    public static $includePattern = array();

    /**
     * @var array
     */
    public static $excludePattern = array();

    /**
     * @var array
     */
    public static $autoAspects = array();

    /**
     * @var boolean
     */
    public static $READ_PARENT_ANNOTATION = false;

    /**
     * @var boolean
     */
    public static $INCLUDE_DECLARED_CLASS = false;

    /**
     * @var array
     */
    public static $SINGLETON_CONTAINERS = array();

    /**
     * @var string
     */
    private static $envPrefix = null;

    /**
     * @var boolean
     */
    private static $filterByEnv = true;

    /**
     * @var array
     */
    private static $commentCache = array();

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
        self::$SINGLETON_CONTAINERS = array();
        self::$includePattern = array();
        self::$excludePattern = array();
        self::$autoAspects    = array();
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
                $namespace = explode('::', $namespace);
            }
        }

        if (is_dir($path)) {
            self::scanDir($path, $namespace, $strict, $pear, $recursive);
        } else if (is_file($path)) {
            self::importInternal($path, $namespace, $pear);
        } else {
            trigger_error(__CLASS__ . " : invalid args. [$path]", E_USER_WARNING);
        }
    }

    /**
     * ファイルシステムを再帰的に検索します。
     *
     * @see seasar::container::S2ApplicationContext::import()
     */
    private static function scanDir($parentPath, $namespace, $strict, $pear, $recursive) {
        $iterator = new DirectoryIterator($parentPath);
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
                $className = implode('::' , $namespace);
            }
            self::$CLASSES[$className] = $filePath;
            seasar::util::ClassLoader::$CLASSES[$className] = $filePath;
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("find class $className : $filePath", __METHOD__);
        } else if (stripos($fileNameRev, 'nocid.') === 0) {
            self::$DICONS[$fileName] = $filePath;
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("find dicon $fileName : $filePath", __METHOD__);
        } else {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("ignore file $fileName : $filePath", __METHOD__);
        }
    }

    /**
     * コンテナを生成してコンポーネントを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string  $key
     * @param string  $namespace
     * @return object
     */
    public static function getComponent($key, $namespace = '') {
        return self::getComponentDef($key, $namespace)->getComponent();
    }

    /**
     * @see seasar::container::S2ApplicationContext::getComponent()
     */
    public static function get($key, $namespace = '') {
        return self::getComponent($key, $namespace);
    }

    /**
     * コンテナを生成して、ComponentDefを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string  $key
     * @param string  $namespace
     * @return seasar::container::ComponentDef
     */
    public static function getComponentDef($key, $namespace = '') {
        if (!array_key_exists($namespace, self::$SINGLETON_CONTAINERS)) {
            self::$SINGLETON_CONTAINERS[$namespace] = self::create($namespace);
        }
        return self::$SINGLETON_CONTAINERS[$namespace]->getComponentDef($key);
    }

    /**
     * importされたクラスとダイコンファイルからS2Containerを生成します。
     *
     * @param string $namespace
     * @return seasar::container::S2Container
     */
    public static function create($namespace = '') {
        $dicons = array_values(self::$DICONS);
        if (count($dicons) > 0) {
            $dicons = self::filter($dicons);
        }
        $classes = array_keys(self::$CLASSES);
        if (count($classes) > 0) {
            $classes = self::filter($classes);
        }
        if (count($classes) > 0) {
            $classes = self::envFilter($classes);
        }
        if (self::$INCLUDE_DECLARED_CLASS) {
            $declaredClasses = array_merge(get_declared_interfaces(), get_declared_classes());
            $declaredClasses = self::filter($declaredClasses);
            $declaredClasses = self::envFilter($declaredClasses);
            $declaredClasses = self::componentAnnotationFilter($declaredClasses);
            $classes = array_values(array_unique(array_merge($classes, $declaredClasses)));
        }
        if (count($dicons) == 0 and count($classes) == 0) {
            seasar::log::S2Logger::getLogger(__CLASS__)->info("dicon, class not found at all. create empty container.", __METHOD__);
            return new seasar::container::impl::S2ContainerImpl();
        }

        $const = seasar::util::Annotation::$CONSTANT;
        seasar::util::Annotation::$CONSTANT = false;
        $container = self::createInternal($dicons, $classes, $namespace);
        seasar::util::Annotation::$CONSTANT = $const;
        return $container;
    }

    /**
     * @S2Componentアノテーションが付いているクラスのみを抽出します。
     *
     * @param array $classes
     * @return array
     */
    private static function componentAnnotationFilter($classes) {
        $filterdClasses = array();
        foreach($classes as $className) {
            $refClass = new ReflectionClass($className);
            if (seasar::util::Annotation::has($refClass, self::COMPONENT_ANNOTATION)) {
                $filterdClasses[] = $className;
            }
        }
        return $filterdClasses;
    }

    /**
     * 指定されたダイコンファイルとクラスからS2Containerを生成します。
     *   - rootのS2Containerを生成します。
     *   - 各ダイコンファイルからS2Containrを生成し、rootのコンテナに子コンテナとして登録します。
     *   - 指定された各クラスに対するComponentDefを全て生成しrootコンテナに登録します。
     *   - 各ComponentDefのセットアップを実施します。
     *
     * @param array $dicons
     * @param array $classes
     * @return seasar::container::S2Container
     */
    public static function createInternal($dicons, $classes, $namespaceArg = '') {
        $container = new seasar::container::impl::S2ContainerImpl();
        foreach ($dicons as $dicon) {
            $child = seasar::container::factory::S2ContainerFactory::includeChild($container, $dicon);
            $child->setRoot($container->getRoot());
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("include dicon : $dicon", __METHOD__);
        }

        $importedClasses = array();
        $registeredComponentDefs = array();
        $namespaceArgDot = $namespaceArg . '.';
        foreach($classes as $clazz) {
            list($cd, $namespace) = self::createComponentDef($clazz);
            if ($cd === null) {
                continue;
            }
            $importedClasses[] = $clazz;
            $registeredComponentDefs[] = $cd;

            if ($namespaceArg !== '') {
                if ($namespace === $namespaceArg) {
                    $namespace = '';
                } else {
                    if (strpos($namespace, $namespaceArgDot) === 0) {
                        $namespace = substr($namespace, strlen($namespaceArgDot));
                    } else {
                        seasar::log::S2Logger::getLogger(__CLASS__)->debug("ignored by namespace : $namespace not in $namespaceArg", __METHOD__);
                        continue;
                    }
                }
            }
            self::registerComponentDef($container, $cd, $namespace);
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("import component : $clazz", __METHOD__);
        }

        foreach($registeredComponentDefs as $cd) {
            self::setupComponentDef($cd);
        }

        return $container;
    }

    /**
     * 渡されたnamespaceをドットで区切って、それぞれについてコンテナを取得または生成します。
     * namespaceが空文字の場合に、渡されたコンテナにコンポーネントを登録します。
     *
     * @param seasar::container::S2Container $container
     * @param seasar::container::ComponentDef $cd
     * @param string|null $namespace
     */
    public static function registerComponentDef(seasar::container::S2Container $container, seasar::container::ComponentDef $cd, $namespace = '') {
        if ($namespace == '') { // 文字列一致、またはnull一致
            $container->register($cd);
        } else {
            $names = preg_split('/\./', $namespace, 2);
            if ($container->hasComponentDef($names[0])) {
                $childContainer = $container->getComponent($names[0]);
                if (!$childContainer instanceof seasar::container::S2Container) {
                    throw new seasar::container::exception::TooManyRegistrationRuntimeException($names[0], array($container->getComponentDef($names[0])->getComponentClass(), new ReflectionClass('seasar::container::impl::S2ContainerImpl')));
                }
            } else {
                $childContainer = new seasar::container::impl::S2ContainerImpl();
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
     * クラスに対するComponentDefを生成します。
     * コンポーネント情報はコメントアノテーションで取得します。
     *
     * @param string $className
     * @return seasar::container::ComponentDef
     */
    public static function createComponentDef($className) {
        $refClass = new ReflectionClass($className);
        if (!seasar::util::Annotation::has($refClass, self::COMPONENT_ANNOTATION)) {
            return array(new seasar::container::impl::ComponentDefImpl($refClass) , null);
        }

        $componentInfo = seasar::util::Annotation::get($refClass, self::COMPONENT_ANNOTATION);
        if (isset($componentInfo['available']) and (boolean)$componentInfo['available'] === false) {
            return null;
        }
        if (isset($componentInfo['name'])) {
            $cd = new seasar::container::impl::ComponentDefImpl($refClass, $componentInfo['name']);
        } else {
            $cd = new seasar::container::impl::ComponentDefImpl($refClass);
        }
        if (isset($componentInfo['instance'])) {
            $cd->setInstanceDef(seasar::container::deployer::InstanceDefFactory::getInstanceDef($componentInfo['instance']));
        }
        if (isset($componentInfo['autoBinding'])) {
            $cd->setAutoBindingDef(seasar::container::assembler::AutoBindingDefFactory::getAutoBindingDef($componentInfo['autoBinding']));
        }
        $namespace = '';
        if (isset($componentInfo['namespace'])) {
            $namespace = $componentInfo['namespace'];
        }
        return array($cd, $namespace);
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
     * @param seasar::container::ComponentDef $cd
     * @return seasar::container::ComponentDef
     */
    public static function setupComponentDef(seasar::container::ComponentDef $cd) {
        $classRef = $cd->getComponentClass();
        $beanDesc = seasar::beans::BeanDescFactory::getBeanDesc($classRef);
        $propDescs = $beanDesc->getPropertyDescs();
        foreach ($propDescs as $propDesc) {
            $ref = $propDesc->getReflection();
            if (self::$READ_PARENT_ANNOTATION === false and 
                $ref->getDeclaringClass()->getName() !== $classRef->getName()) {
                continue;
            }
            if (seasar::util::Annotation::has($ref, self::BINDING_ANNOTATION)) {
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
                seasar::util::Annotation::has($methodRef, self::ASPECT_ANNOTATION)) {
                self::setupMethodAspectDef($cd, $methodRef);
            }
        }
        if (!$classRef->isFinal() and
            seasar::util::Annotation::has($classRef, self::ASPECT_ANNOTATION)) {
            self::setupClassAspectDef($cd, $classRef);
        }

        if (seasar::util::Annotation::has($cd->getComponentClass(), self::META_ANNOTATION)) {
            self::setupClassMetaDef($cd, $classRef);
        }

        foreach(self::$autoAspects as $aspectInfo) {
            if (preg_match($aspectInfo['componentPattern'], $cd->getComponentName()) or
                preg_match($aspectInfo['componentPattern'], $cd->getComponentClass()->getName())) {
                self::setupAspectDef($cd, $aspectInfo);
            }
        }

        return $cd;
    }

    /**
     * PropertyDefをセットアップします。
     *
     * @param seasar::container::ComponentDef $cd
     * @param ReflectionClass $reflection
     * @param string $propName
     */
    private static function setupPropertyDef(ComponentDef $cd, $reflection, $propName) {
        $propInfo = seasar::util::Annotation::get($reflection, self::BINDING_ANNOTATION);
        if (isset($propInfo[0])) {
            $propertyDef = new seasar::container::impl::PropertyDef($propName);
            $cd->addPropertyDef($propertyDef);
            if ($cd->getContainer()->hasComponentDef($propInfo[0])) {
                $propertyDef->setChildComponentDef($cd->getContainer()->getComponentDef($propInfo[0]));
            } else {
                $propertyDef->setExpression($propInfo[0]);
            }
        } else {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("binding annotation found. cannot get values.", __METHOD__);
        }
    }

    /**
     * クラスに指定されているAspectをセットアップします。
     *
     * @param seasar::container::ComponentDef $cd
     * @param ReflectionClass $reflection
     */
    private static function setupClassAspectDef(ComponentDef $cd, ReflectionClass $classRef) {
        $annoInfo = seasar::util::Annotation::get($classRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        self::setupAspectDef($cd, $annoInfo);
    }

    /**
     * メソッドに指定されているAspectをセットアップします。
     *
     * @param seasar::container::ComponentDef $cd
     * @param ReflectionMethod $methodRef
     */
    private static function setupMethodAspectDef(ComponentDef $cd, ReflectionMethod $methodRef) {
        $annoInfo = seasar::util::Annotation::get($methodRef, self::ASPECT_ANNOTATION);
        if (count($annoInfo) === 0) {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("method aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        $annoInfo['pointcut'] = '^' . $methodRef->getName() . '$';
        self::setupAspectDef($cd, $annoInfo);
    }

    /**
     * AspectDefをセットアップします。
     *
     * @param seasar::container::ComponentDef $cd
     * @param array $annoInfo
     */
    private static function setupAspectDef(ComponentDef $cd, array $annoInfo) {
        if (isset($annoInfo['interceptor'])) {
            if (isset($annoInfo['pointcut'])) {
                $pointcut = new seasar::aop::Pointcut($annoInfo['pointcut']);
            } else {
                $pointcut = new seasar::aop::Pointcut($cd->getComponentClass());
            }
            $aspectDef = new seasar::container::impl::AspectDef($pointcut);
            $cd->addAspectDef($aspectDef);
            if ($cd->getContainer()->hasComponentDef($annoInfo['interceptor'])) {
                $aspectDef->setChildComponentDef($cd->getContainer()->getComponentDef($annoInfo['interceptor']));
            } else {
                $aspectDef->setExpression($annoInfo['interceptor']);
            }
        } else {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("invalid aspect info. cannot get interceptor value.", __METHOD__);
        }
    }

    /**
     * MetaDefをセットアップします。
     *
     * @param seasar::container::ComponentDef $cd
     * @param ReflectionClass $classRef
     */
    private static function setupClassMetaDef(ComponentDef $cd, ReflectionClass $classRef) {
        $annoInfo = seasar::util::Annotation::get($classRef, self::META_ANNOTATION);
        if (count($annoInfo) === 0) {
            seasar::log::S2Logger::getLogger(__CLASS__)->debug("class aspect annotation found. cannot get values.", __METHOD__);
            return;
        }
        foreach($annoInfo as $key => $val) {
            $metaDef = new seasar::container::impl::MetaDef($key);
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
     * 環境変数によってフィルタします。
     * 環境変数が「test」の場合に、クラス群のなかに、TestHogeクラスと、Hogeクラスが存在した場合、
     * TestHogeクラスのみを抽出します。
     *
     * @param array $items コンポーネントとして取り込まれるクラス群
     * @return array
     */
    public static function envFilter(array $items) {
        $envPrefix = null;
        if (self::$filterByEnv and seasar::container::Config::$ENVIRONMENT !== null) {
            if (self::$envPrefix === null) {
                # 環境変数が「test」の場合、「Test」をプレフィックス値とする
                $envPrefix = ucfirst(strtolower(seasar::container::Config::$ENVIRONMENT));
            } else {
                $envPrefix = ucfirst(strtolower(self::$envPrefix));
            }
        } else {
            return $items;
        }
        $classes = array();
        foreach ($items as $item) {
            $envClassName = $envPrefix . $item;
            if (in_array($envClassName, $items)) {
                continue;
            } else {
                $classes[] = $item;
            }
        }
        return $classes;
    }

    /**
     * includeパターン、excludeパターンによってフィルタします。
     *
     * @param array $items
     * @return array
     */
    public static function filter($items) {
        if (0 < count(self::$includePattern)) {
            $includes = array();
            foreach($items as $item) {
                foreach(self::$includePattern as $pattern){
                    if (preg_match($pattern, $item)) {
                        $includes[] = $item;
                        break;
                    }
                }
            }
            $items = $includes;
        }

        if (0 == count(self::$excludePattern)) {
            return $items;
        }

        $includes = array();
        foreach($items as $item) {
            $matched = false;
            foreach(self::$excludePattern as $pattern){
                if (preg_match($pattern, $item)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $includes[] = $item;
            }
        }
        return $includes;
    }

    /**
     * includeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addIncludePattern($pattern) {
        if (is_string($pattern)) {
            if (0 === strpos($pattern, '/')) {
                self::$includePattern[] = $pattern;
            } else {
                self::$includePattern[] = "/$pattern/";
            }
        } else {
            throw new ::InvalidArgumentException('expected string.');
        }
    }

    /**
     * includeパターンを返します。
     *
     * @return array
     */
    public static function getIncludePattern() {
        return self::$includePattern;
    }

    /**
     * includeパターンをセットします。
     *
     * @param array|string $pattern
     */
    public static function setIncludePattern($pattern = array()) {
        if (is_string($pattern)) {
            if (0 === strpos($pattern, '/')) {
                self::$includePattern = array($pattern);
            } else {
                self::$includePattern = array("/$pattern/");
            }
        } else if (is_array($pattern)) {
            self::$includePattern = $pattern;
        } else {
            throw new ::InvalidArgumentException('expected array|string.');
        }
    }

    /**
     * excludeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addExcludePattern($pattern) {
        if (is_string($pattern)) {
            if (0 === strpos($pattern, '/')) {
                self::$excludePattern[] = $pattern;
            } else {
                self::$excludePattern[] = "/$pattern/";
            }
        } else {
            throw new ::InvalidArgumentException('expected string.');
        }
    }

    /**
     * excludeパターンを返します。
     *
     * @return array
     */
    public static function getExcludePattern() {
        return self::$excludePattern;
    }

    /**
     * excludeパターンをセットします。
     *
     * @param array $pattern
     */
    public static function setExcludePattern($pattern = array()) {
        if (is_string($pattern)) {
            if (0 === strpos($pattern, '/')) {
                self::$excludePattern = array($pattern);
            } else {
                self::$excludePattern = array("/$pattern/");
            }
        } else if (is_array($pattern)) {
            self::$excludePattern = $pattern;
        } else {
            throw new ::InvalidArgumentException('expected array|string.');
        }
    }

    /**
     * 環境フィルタを適用するかどうかを設定します。
     *
     * @param boolean $val
     */
    public static function setFilterByEnv($val = true) {
        self::$filterByEnv = $val;
    }

    /**
     * 環境フィルタを適用した場合のクラスプレフィックス名を設定します。
     *
     * @param string $val
     */
    public static function setEnvPrefix($val = null) {
        self::$envPrefix = $val;
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
     * 自動アスペクト情報を登録します。
     * aspectInfoは、@S2Aspectアノテーション結果に合わせます。
     *
     * @param string $componentPattern
     * @param string $interceptor
     * @param string $pointcut
     */
    public static function registerAspect($componentPattern, $interceptor, $pointcut = null) {
        if ($pointcut == null) {
            $aspectInfo = array('componentPattern' => $componentPattern,
                                'interceptor'      => $interceptor);
        } else {
            $aspectInfo = array('componentPattern' => $componentPattern,
                                'interceptor'      => $interceptor,
                                'pointcut'         => $pointcut);
        }
        self::$autoAspects[] = $aspectInfo;
    }
}
