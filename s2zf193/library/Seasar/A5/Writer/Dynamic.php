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
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Writer_Dynamic implements Seasar_A5_Writer {

    /**
     * @see Seasar_A5_Writer::write()
     */
    public function write($contents) {
        $contents = substr(trim($contents), 5); // PHPタグの削除
        if (!class_exists($this->className, false)) {
            //echo PHP_EOL . $contents . PHP_EOL . PHP_EOL;
            \seasar\util\EvalUtil::execute($contents);
        }
    }

    /**
     * @see Seasar_A5_Writer::setResource()
     */
    public function setResource($resource){
    }

    /**
     * @see Seasar_A5_Writer::setClassName()
     */
    public function setClassName($className){
        $this->className = $className;
    }

}
