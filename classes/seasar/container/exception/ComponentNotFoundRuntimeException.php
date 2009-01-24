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
 * 取得しようとしたコンポーネントがS2コンテナ上に見つからなかった場合にスローされます。
 * <p>
 * コンポーネントの検索には、 コンポーネントキー(キーオブジェクト)として、 クラス(インターフェース)またはコンポーネント名が使用できますが、
 * どちらの場合でもコンポーネントが見つからなかった場合には、 この例外がスローされます。
 * </p>
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
class ComponentNotFoundRuntimeException extends \seasar\exception\S2RuntimeException {

    /**
     * @var string
     */
    private $componentKey;

    /**
     * コンポーネントの検索に用いたコンポーネントキーを指定して、
     * <b>ComponentNotFoundRuntimeException</b>を構築します。
     * 
     * @param string $componentKey コンポーネントキー
     */
    public function __construct($componentKey) {
        parent::__construct(109, (array)$componentKey);
        $this->componentKey = $componentKey;
    }
    
    /**
     * コンポーネントの検索に用いたコンポーネントキーを返します。
     * 
     * @return string コンポーネントキー
     */
    public function getComponentKey() {
        return $this->componentKey;
    }
}
