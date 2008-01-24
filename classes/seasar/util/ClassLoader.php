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
     * @param array $namespace
     * @param boolean $strict
     * @param boolean $pear
     * @param boolean $recursive
     */
    public static function import($dirPath, array $namespace = array(), $strict = false, $pear = false, $recursive = true) {
        $iterator = new DirectoryIterator($dirPath);
        while($iterator->valid()) {
            if ($iterator->isDot()) {
                $iterator->next();
                continue;
            }
            if ($iterator->isFile()) {
                $matches = array();
                if (preg_match('/^([^\.]+?)\..*php$/', $iterator->getFileName(), $matches)) {
                    if ($pear) {
                        $className = implode('_', array_merge($namespace, (array)$matches[1]));
                    } else {
                        $className = implode('::', array_merge($namespace, (array)$matches[1]));
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
    'seasar::aop::Config' => SEASAR_ROOT_DIR . '/classes/seasar/aop/Config.php',
    'seasar::aop::exception::AbstractMethodInvocationRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/aop/exception/AbstractMethodInvocationRuntimeException.php',
    'seasar::aop::exception::CacheDirectoryUnwritableException' => SEASAR_ROOT_DIR . '/classes/seasar/aop/exception/CacheDirectoryUnwritableException.php',
    'seasar::aop::exception::EnhancedClassGenerationRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/aop/exception/EnhancedClassGenerationRuntimeException.php',
    'seasar::aop::impl::NestedMethodInvocation' => SEASAR_ROOT_DIR . '/classes/seasar/aop/impl/NestedMethodInvocation.php',
    'seasar::aop::impl::S2MethodInvocationImpl' => SEASAR_ROOT_DIR . '/classes/seasar/aop/impl/S2MethodInvocationImpl.php',
    'seasar::aop::interceptor::AbstractAfterInterceptor' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractAfterInterceptor.php',
    'seasar::aop::interceptor::AbstractAroundInterceptor' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractAroundInterceptor.php',
    'seasar::aop::interceptor::AbstractBeforeInterceptor' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/AbstractBeforeInterceptor.php',
    'seasar::aop::interceptor::InterceptorChain' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/InterceptorChain.php',
    'seasar::aop::interceptor::MockInterceptor' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/MockInterceptor.php',
    'seasar::aop::interceptor::TraceInterceptor' => SEASAR_ROOT_DIR . '/classes/seasar/aop/interceptor/TraceInterceptor.php',
    'seasar::aop::MethodInvocation' => SEASAR_ROOT_DIR . '/classes/seasar/aop/MethodInvocation.php',
    'seasar::Config' => SEASAR_ROOT_DIR . '/classes/seasar/Config.php',
    'seasar::container::Config' => SEASAR_ROOT_DIR . '/classes/seasar/container/Config.php',
    'seasar::container::deployer::PrototypeComponentDeployer' => SEASAR_ROOT_DIR . '/classes/seasar/container/deployer/PrototypeComponentDeployer.php',
    'seasar::container::exception::CircularIncludeRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/CircularIncludeRuntimeException.php',
    'seasar::container::exception::ComponentNotFoundRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/ComponentNotFoundRuntimeException.php',
    'seasar::container::exception::ContainerNotRegisteredRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/ContainerNotRegisteredRuntimeException.php',
    'seasar::container::exception::CyclicInstantiationRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/CyclicInstantiationRuntimeException.php',
    'seasar::container::exception::CyclicReferenceRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/CyclicReferenceRuntimeException.php',
    'seasar::container::exception::IllegalAutoBindingDefRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/IllegalAutoBindingDefRuntimeException.php',
    'seasar::container::exception::IllegalConstructorRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/IllegalConstructorRuntimeException.php',
    'seasar::container::exception::IllegalContainerBuilderRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/IllegalContainerBuilderRuntimeException.php',
    'seasar::container::exception::IllegalInstanceDefRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/IllegalInstanceDefRuntimeException.php',
    'seasar::container::exception::PropertyNotFoundRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/PropertyNotFoundRuntimeException.php',
    'seasar::container::exception::TooManyRegistrationRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/container/exception/TooManyRegistrationRuntimeException.php',
    'seasar::container::impl::AbstractTypehintPropertyDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/impl/AbstractTypehintPropertyDef.php',
    'seasar::container::impl::InitMethodDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/impl/InitMethodDef.php',
    'seasar::container::impl::MetaDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/impl/MetaDef.php',
    'seasar::container::impl::PropertyDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/impl/PropertyDef.php',
    'seasar::container::impl::S2ContainerComponentDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/impl/S2ContainerComponentDef.php',
    'seasar::container::MethodDef' => SEASAR_ROOT_DIR . '/classes/seasar/container/MethodDef.php',
    'seasar::exception::AnnotationNotFoundException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/AnnotationNotFoundException.php',
    'seasar::exception::AnnotationNotSupportedException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/AnnotationNotSupportedException.php',
    'seasar::exception::DOMException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/DOMException.php',
    'seasar::exception::FileNotFoundException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/FileNotFoundException.php',
    'seasar::exception::IllegalPropertyRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/IllegalPropertyRuntimeException.php',
    'seasar::exception::MethodNotFoundRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/MethodNotFoundRuntimeException.php',
    'seasar::exception::NotYetImplementedException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/NotYetImplementedException.php',
    'seasar::exception::PropertyNotFoundRuntimeException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/PropertyNotFoundRuntimeException.php',
    'seasar::exception::UnsupportedOperationException' => SEASAR_ROOT_DIR . '/classes/seasar/exception/UnsupportedOperationException.php',
    'seasar::util::ClassLoader' => SEASAR_ROOT_DIR . '/classes/seasar/util/ClassLoader.php',
    'seasar::util::MethodUtil' => SEASAR_ROOT_DIR . '/classes/seasar/util/MethodUtil.php',
    'S2ContainerApplicationContext' => SEASAR_ROOT_DIR . '/classes/legacy/S2ContainerApplicationContext.php',
    'S2ContainerFactory' => SEASAR_ROOT_DIR . '/classes/legacy/S2ContainerFactory.php');
