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
 * seasar用の例外です。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.exception
 * @author    klove
 */
namespace seasar\exception;
class S2RuntimeException extends \RuntimeException {

    /**
     * @var array
     */
    public static $MESSAGES = array();

    /**
     * @var integer
     */
    protected $code  = null;

    /**
     * @var array
     */
    private $args  = null;

    /**
     * @var Exception
     */
    private $couse = null;

    /**
     * S2RuntimeExceptionを構築します。
     *
     * @param integer $code
     * @param array $args
     * @param Exception $couse
     */
    public function __construct($code, array $args = array(), Exception $couse = null) {
        $this->code  = $code;
        $this->args  = $args;
        $this->couse = $couse;
        if (isset(self::$MESSAGES[$code])) {
            $msg = \seasar\util\EvalUtil::formatExecute(self::$MESSAGES[$code], array('args' => $args));
        } else {
            $msgTmp = array();
            foreach ($args as $arg) {
                $msgTmp[] = \seasar\util\StringUtil::mixToString($arg);
            }
            $msg = '[' . implode(', ', $msgTmp) . ']';
        }
        parent::__construct($msg, $code);
    }

    /**
     * 例外情報を返します。
     *
     * @return array
     */
    public function getArgs() {
        return $this->args;
    }

    /**
     * 例外コードを返します。
     *
     * @return integer
     */
    public function getCouse() {
        return $this->couse;
    }
}
