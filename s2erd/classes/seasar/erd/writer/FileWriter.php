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
 * ファイルに出力します。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\writer
 * @author    klove
 */
namespace seasar\erd\writer;
class FileWriter implements \seasar\erd\Writer {

    /**
     * @var string
     */
    private $filePath = null;

    /**
     * @see \seasar\erd\Writer::write()
     */
    public function write($contents) {
        if (file_exists($this->filePath)) {
            echo "exists : {$this->filePath}" . PHP_EOL;
            return;
        }

        echo "create : {$this->filePath}" . PHP_EOL;
        file_put_contents($this->filePath, $contents);
    }

    /**
     * @see \seasar\erd\Writer::setFilePath()
     */
    public function setFilePath($filePath){
        $this->filePath = $filePath;
    }

    /**
     * @see \seasar\erd\Writer::setClassName()
     */
    public function setClassName($className){
    }

}
