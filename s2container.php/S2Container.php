<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 * S2Containerの設定ファイルです。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   \
 * @author    klove
 */

if (!defined('S2CONTAINER_ROOT_DIR')) {
    define('S2CONTAINER_ROOT_DIR', dirname(__FILE__));
}

require_once(S2CONTAINER_ROOT_DIR . '/S2ContainerCore.php');
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/util/ClassLoader.php');
if (function_exists('spl_autoload_register')) {
    spl_autoload_register(array('\seasar\util\ClassLoader', 'load'));
}

require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/Config.php');
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/container/Config.php');
require_once(S2CONTAINER_ROOT_DIR . '/classes/seasar/aop/Config.php');


/**
 * @see seasar\container\S2ApplicationContext::init()
 */
function s2init() {
    seasar\container\S2ApplicationContext::init();
}

/**
 * @see seasar\container\S2ApplicationContext::register()
 */
function s2register($clazz = null) {
    return seasar\container\S2ApplicationContext::register($clazz);
}

/**
 * @see seasar\container\S2ApplicationContext::register()
 */
function s2component($clazz = null) {
    return seasar\container\S2ApplicationContext::register($clazz);
}

/**
 * @see seasar\container\S2ApplicationContext::register()
 */
function s2comp($clazz = null) {
    return seasar\container\S2ApplicationContext::register($clazz);
}

/**
 * @see seasar\container\S2ApplicationContext::registerAspect()
 */
function s2aspect($interceptor = null, $componentPattern = null, $pointcut = null) {
    return seasar\container\S2ApplicationContext::registerAspect($interceptor, $componentPattern, $pointcut);
}

/**
 * @see seasar\container\S2ApplicationContext::import()
 */
function s2import($path, $namespace = array(), $strict = false, $pear = false, $recursive = true) {
    seasar\container\S2ApplicationContext::import($path, $namespace, $strict, $pear, $recursive);
}

/**
 * @see seasar\container\S2ApplicationContext::get()
 */
function s2get($key, $namespaces = array()) {
    return seasar\container\S2ApplicationContext::get($key, $namespaces);
}

/**
 * @see seasar\container\S2ApplicationContext::create()
 */
function s2create($namespaces = array()) {
    return seasar\container\S2ApplicationContext::create($namespaces);
}

