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
 * 不正なContainerBuilderが指定された場合にスローされます。
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
class IllegalContainerBuilderRuntimeException extends \seasar\exception\S2RuntimeException {

    /**
     * @var string
     */
    private $containerBuilderName;

    /**
     * IllegalContainerBuilderRuntimeExceptionを構築します。
     *
     * @param string $containerBuilderName 不正なContainerBuilder名
     */
    public function __construct($containerBuilderName) {
        $this->containerBuilderName = $containerBuilderName;
        parent::__construct(108, (array)$containerBuilderName);
    }

    /**
     * 例外の原因となった不正なContainerBuilder名を返します。
     *
     * @return string
     */
    public function getContainerBuilderName() {
        return $this->containerBuilderName;
    }
}
