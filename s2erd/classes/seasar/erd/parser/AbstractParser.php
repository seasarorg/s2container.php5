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
 * パースクラス
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\parser
 * @author    klove
 */
namespace seasar\erd\parser;
abstract class AbstractParser implements \seasar\erd\Parser {
    /**
     * コメントに埋め込まれたPHPソースを切り出す。
     *
     * @param string $comment
     * @return array
     */
    public function explodeComment($comment) {
        $matches = array();
        if (preg_match('/<\?php(.+?)\?>/s', $comment, $matches)) {
            return array(preg_replace('/<\?php.+?\?>/s', '', $comment), $matches[1]);
        }
        return array($comment, '');
    }

    /**
     * @param string $erdFile
     * @return null
     */
    public function setErdFile($erdFile) {
        $this->erdFile = $erdFile;
    }
}
