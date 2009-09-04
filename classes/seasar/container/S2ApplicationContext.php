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
    public static $COMPONENT_INFOS = array();

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
     * @var array
     */
    public static $SINGLETON_CONTAINER = null;

    /**
     * 初期化処理を行います。
     */
    public static function init() {
        self::$CLASSES = array();
        self::$COMPONENT_INFOS = array();
        self::$SINGLETON_CONTAINER = null;
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
        \seasar\container\factory\ComponentDefBuilder::$READ_PARENT_ANNOTATION = $val;
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
            throw new \seasar\exception\FileNotFoundException($path);
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
        } else {
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug("ignore file $fileName : $filePath", __METHOD__);
        }
    }

    /**
     * コンテナを生成してコンポーネントを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string $key
     * @return object
     */
    public static function getComponent($key) {
        return self::getComponentDef($key)->getComponent();
    }

    /**
     * @see \seasar\container\S2ApplicationContext::getComponent()
     */
    public static function get($key) {
        return self::getComponent($key);
    }

    /**
     * コンテナを生成して、ComponentDefを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string $key
     * @return \seasar\container\ComponentDef
     */
    public static function getComponentDef($key) {
        return self::createSingletonContainer()->getComponentDef($key);
    }

    /**
     * コンテナを生成して、ComponentDefが存在するかどうかを返します。生成したコンテナはsingletonとして保持します。
     *
     * @param string $key
     * @return boolean
     */
    public static function hasComponentDef($key) {
        return self::createSingletonContainer()->hasComponentDef($key);
    }

    /**
     * importされたクラスとダイコンファイルからS2Containerを生成します。
     * 生成されたS2ContainerをSingletonとして登録します。
     *
     * @param boolean $force
     * @return seasar\container\S2Container
     */
    public static function createSingletonContainer($force = false) {
        if (self::$SINGLETON_CONTAINER === null || $force) {
            self::$SINGLETON_CONTAINER = self::create();
        }
        return self::$SINGLETON_CONTAINER;
    }

    /**
     * importされたクラスとダイコンファイルからS2Containerを生成します。
     *
     * @return \seasar\container\S2Container
     */
    public static function create() {
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

        if (count($infos) == 0) {
            \seasar\log\S2Logger::getLogger(__CLASS__)->info("class not found at all. create empty container.", __METHOD__);
            return new \seasar\container\impl\S2ContainerImpl();
        }
        return self::createInternal($infos);
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
        if (!\seasar\util\Annotation::has($refClass, \seasar\container\factory\ComponentDefBuilder::COMPONENT_ANNOTATION)) {
            return new \seasar\container\ComponentInfoDef($refClass);
        }

        $componentInfo = \seasar\util\Annotation::get($refClass, \seasar\container\factory\ComponentDefBuilder::COMPONENT_ANNOTATION);
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

        return $info;
    }

    /**
     * 指定されたダイコンファイルとクラスからS2Containerを生成します。
     *   - rootのS2Containerを生成します。
     *   - 各ダイコンファイルからS2Containrを生成し、rootのコンテナに子コンテナとして登録します。
     *   - 指定された各クラスに対するComponentDefを全て生成しrootコンテナに登録します。
     *   - 各ComponentDefのセットアップを実施します。
     *
     * @param array $infos
     * @return \seasar\container\S2Container
     */
    public static function createInternal($infos) {
        $container = new \seasar\container\impl\S2ContainerImpl();

        $registeredComponentDefs = array();
        foreach($infos as $info) {
            $cd = self::createComponentDef($info);
            $registeredComponentDefs[] = $cd;
            $container->register($cd);
            \seasar\log\S2Logger::getLogger(__CLASS__)->debug('import component : ' . $info->getClassName(), __METHOD__);
        }

        \seasar\log\S2Logger::getLogger(__CLASS__)->info('imported components : ' . count($registeredComponentDefs), __METHOD__);
        foreach($registeredComponentDefs as $cd) {
            \seasar\container\factory\ComponentDefBuilder::setupComponentDef($cd);
            foreach(self::$autoAspects as $aspectInfo) {
                if ($aspectInfo->applicable($cd)) {
                    \seasar\container\factory\ComponentDefBuilder::setupAspectDef($cd, $aspectInfo->toAnnotationArray());
                }
            }
        }

        return $container;
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
        if ($info->hasConstructClosure()) {
            $cd->setConstructClosure($info->getConstructClosure());
        }
        return $cd;
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
        self::$includeClassPatterns = (array)$pattern;
    }

    /**
     * includeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addIncludePattern($pattern) {
        self::$includeClassPatterns[] = $pattern;
    }

    /**
     * excludeパターンをセットします。
     *
     * @param string $pattern
     */
    public static function setExcludePattern($pattern = array()) {
        self::$excludeClassPatterns = (array)$pattern;
    }

    /**
     * excludeパターンを追加します。
     *
     * @param string $pattern
     */
    public static function addExcludePattern($pattern) {
        self::$excludeClassPatterns[] = $pattern;
    }

}
