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
 * diconファイルなどの設定情報に対応するS2コンテナが、 コンテナツリーに登録されていなかった場合にスローされます。
 * 
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.exception
 * @author    klove
 */
namespace seasar\container\exception;
class ContainerNotRegisteredRuntimeException extends \seasar\exception\S2RuntimeException {

    /**
     * @var string
     */
    private $path;

    /**
     * 登録されていなかった設定情報のパスを指定して、
     * <d>S2Container_ContainerNotRegisteredRuntimeException</d>を構築します。
     * 
     * @param string $path 登録されていなかった設定情報のパス
     */
    public function __construct($path) {
        $this->path = $path;
        parent::__construct(110, (array)$path);
    }

    /**
     * コンテナツリーに登録されていなかった設定情報のパスを返します。
     * 
     * @return string 登録されていなかった設定情報のパス
     */
    public function getPath() {
        return $this->path;
    }
}
