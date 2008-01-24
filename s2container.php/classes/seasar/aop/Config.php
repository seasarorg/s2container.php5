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
 * seasar::aopの設定を行うクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
abstract class Config {
    /**
     * アスペクト対象クラスのEnhanceクラスを生成するクラスを指定します。
     * @var seasar::aop::EnhancedClassGenerator
     */
    public static $ENHANCED_CLASS_GENERATOR = 'seasar::aop::EnhancedClassGenerator';

    /**
     * @var boolean
     */
    public static $CACHING = false;

    /**
     * @var string
     */
    public static $CACHE_DIR = null;
}

/**
 * S2RuntimeExceptionへの例外メッセージの登録
 */
seasar::exception::S2RuntimeException::$MESSAGES[202] = '"cannot invoke abstract method. [$args[0]->$args[1]()]"';
seasar::exception::S2RuntimeException::$MESSAGES[203] = '"class $args[0] already has property or method. [$args[1]]"';
seasar::exception::S2RuntimeException::$MESSAGES[204] = '"cache directory : $args[0]"';

