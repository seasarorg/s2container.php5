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
 * S2Erdの設定ファイルです。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.1.0
 * @package   \
 * @author    klove
 */

if (!defined('S2ERD_ROOT_DIR')) {
    define('S2ERD_ROOT_DIR', dirname(__FILE__));
}

if (!defined('S2CONTAINER_ROOT_DIR')) {
    require_once('S2Container.php');
}

require_once(S2ERD_ROOT_DIR . '/classes/seasar/erd/Config.php');
\seasar\util\ClassLoader::import(S2ERD_ROOT_DIR . '/classes');

