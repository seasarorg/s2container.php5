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
 * ダイコンファイルの読み込み時に、CircularIncludeが発生した場合にスローされる例外クラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.exception
 * @author    klove
 */
namespace seasar\container\exception;
class CircularIncludeRuntimeException extends \seasar\exception\S2RuntimeException {

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $paths;

    /**
     * CircularIncludeRuntimeExceptionを構築します。
     *
     * @param string $path
     * @param array  $paths
     */
    public function __construct($path, array $paths) {
        parent::__construct(114, array($path, $this->getPathway($path, $paths)));
        $this->path = $path;
        $this->paths = $paths;
    }

    /**
     * CircularIncludeが発生したダイコンファイルを返します。
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * CircularIncludeが発生したダイコンファイル群を返します。
     *
     * @return array
     */
    public function getPaths() {
        return $this->paths;
    }

    /**
     * CircularIncludeが発生したダイコンファイルを含み、Includeされた順番でダイコンファイルを示します。
     *
     * @param string $path
     * @param array $paths
     * @return string
     */
    private function getPathway($path, array $paths) {
        $paths[] = $path;
        return '"' . implode('" include "', $paths) . '"';
    }
}
