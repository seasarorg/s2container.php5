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
 * Seasar_A5の設定ファイルです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar
 * @author    klove
 */


define('S2A5_ROOT', dirname(__FILE__) . '/A5');
define('S2A5_ENCODING', 'UTF-8');
define('S2A5_MODEL_PACKAGE', 'Model_DbTable');
define('S2A5_MODEL_SUPER_CLASS', 'Abstract');
define('S2A5_NS', 's2-a5');

require_once(dirname(dirname(dirname(__FILE__))) . '/application/configs/s2.php');
