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
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar::util;
class ClassLoader {

    /**
     * @var array
     */
    public static $CLASSES = array();

    /**
     * クラス定義のrequireを実施します。
     *
     * @param string $className
     * @return boolean
     */
    public static function load($className) {
        if (isset(self::$CLASSES[$className])) {
            require_once(self::$CLASSES[$className]);
            return true;
        }
        return false;
    }

    /**
     * クラス定義ファイルをファイルシステムから検索します。
     *
     * @param string $dirPath
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
    public static function import($dirPath, $namespace = array(), $strict = false, $pear = false, $recursive = true) {
        $separator = $pear ? '_' : '::';
        if (is_string($namespace)) {
            $namespace = explode($separator, $namespace);
        }

        $iterator = new DirectoryIterator($dirPath);
        while($iterator->valid()) {
            if (strpos($iterator->getFilename(), '.') === 0) {
                $iterator->next();
                continue;
            }
            if ($iterator->isFile()) {
                $fileName = $iterator->getFileName();
                $fileNameRev = strrev($fileName);
                if (stripos($fileNameRev, 'php.') === 0) {
                    $className = substr($fileName, 0, strpos($fileName, '.'));
                    if (0 < count($namespace)) {
                        $className = implode($separator, $namespace) . $separator . $className;
                    }
                    self::$CLASSES[$className]= $iterator->getRealPath();
                }
            } else if ($recursive and $iterator->isDir()) {
                if ($strict) {
                    self::import($iterator->getRealPath(), $namespace, $strict, $pear);
                } else {
                    self::import($iterator->getRealPath(), array_merge($namespace, (array)$iterator->getFileName()), $strict, $pear);
                }
            }
            $iterator->next();
        }
    }
}

seasar::util::ClassLoader::$CLASSES = array(
    'seasar::aop::Config' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/Config.php',
    'seasar::aop::exception::AbstractMethodInvocationRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/exception/AbstractMethodInvocationRuntimeException.php',
    'seasar::aop::exception::CacheDirectoryUnwritableException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/exception/CacheDirectoryUnwritableException.php',
    'seasar::aop::exception::EnhancedClassGenerationRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/exception/EnhancedClassGenerationRuntimeException.php',
    'seasar::aop::impl::NestedMethodInvocation' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/impl/NestedMethodInvocation.php',
    'seasar::aop::interceptor::AbstractAfterInterceptor' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractAfterInterceptor.php',
    'seasar::aop::interceptor::AbstractAroundInterceptor' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractAroundInterceptor.php',
    'seasar::aop::interceptor::AbstractBeforeInterceptor' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractBeforeInterceptor.php',
    'seasar::aop::interceptor::InterceptorChain' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/InterceptorChain.php',
    'seasar::aop::interceptor::MockInterceptor' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/MockInterceptor.php',
    'seasar::aop::interceptor::TraceInterceptor' => S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/interceptor/TraceInterceptor.php',
    'seasar::beans::AccessorMethodPropertyDesc' => S2CONTAINER_ROOT_DIR . '/classes/seasar/beans/AccessorMethodPropertyDesc.php',
    'seasar::Config' => S2CONTAINER_ROOT_DIR . '/classes/seasar/Config.php',
    'seasar::container::Config' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/Config.php',
    'seasar::container::deployer::PrototypeComponentDeployer' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/deployer/PrototypeComponentDeployer.php',
    'seasar::container::exception::CircularIncludeRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/CircularIncludeRuntimeException.php',
    'seasar::container::exception::ComponentNotFoundRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/ComponentNotFoundRuntimeException.php',
    'seasar::container::exception::ContainerNotRegisteredRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/ContainerNotRegisteredRuntimeException.php',
    'seasar::container::exception::CyclicInstantiationRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/CyclicInstantiationRuntimeException.php',
    'seasar::container::exception::CyclicReferenceRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/CyclicReferenceRuntimeException.php',
    'seasar::container::exception::IllegalAutoBindingDefRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/IllegalAutoBindingDefRuntimeException.php',
    'seasar::container::exception::IllegalConstructorRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/IllegalConstructorRuntimeException.php',
    'seasar::container::exception::IllegalContainerBuilderRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/IllegalContainerBuilderRuntimeException.php',
    'seasar::container::exception::IllegalInstanceDefRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/IllegalInstanceDefRuntimeException.php',
    'seasar::container::exception::PropertyNotFoundRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/PropertyNotFoundRuntimeException.php',
    'seasar::container::exception::TooManyRegistrationRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/exception/TooManyRegistrationRuntimeException.php',
    'seasar::container::factory::S2ContainerBuilder' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/factory/S2ContainerBuilder.php',
    'seasar::container::factory::S2ContainerFactory' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/factory/S2ContainerFactory.php',
    'seasar::container::factory::XmlS2ContainerBuilder' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/factory/XmlS2ContainerBuilder.php',
    'seasar::container::impl::InitMethodDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/impl/InitMethodDef.php',
    'seasar::container::impl::MetaDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/impl/MetaDef.php',
    'seasar::container::impl::PropertyDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/impl/PropertyDef.php',
    'seasar::container::impl::S2ContainerComponentDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/impl/S2ContainerComponentDef.php',
    'seasar::container::impl::TooManyRegistrationComponentDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/impl/TooManyRegistrationComponentDef.php',
    'seasar::container::MethodDef' => S2CONTAINER_ROOT_DIR . '/classes/seasar/container/MethodDef.php',
    'seasar::exception::AnnotationNotFoundException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/AnnotationNotFoundException.php',
    'seasar::exception::AnnotationNotSupportedException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/AnnotationNotSupportedException.php',
    'seasar::exception::DOMException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/DOMException.php',
    'seasar::exception::FileNotFoundException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/FileNotFoundException.php',
    'seasar::exception::IllegalPropertyRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/IllegalPropertyRuntimeException.php',
    'seasar::exception::MethodNotFoundRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/MethodNotFoundRuntimeException.php',
    'seasar::exception::NotYetImplementedException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/NotYetImplementedException.php',
    'seasar::exception::PropertyNotFoundRuntimeException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/PropertyNotFoundRuntimeException.php',
    'seasar::exception::UnsupportedOperationException' => S2CONTAINER_ROOT_DIR . '/classes/seasar/exception/UnsupportedOperationException.php',
    'seasar::util::ClassLoader' => S2CONTAINER_ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
