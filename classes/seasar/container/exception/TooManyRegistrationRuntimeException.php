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
 * 1つのキーに複数のコンポーネントが登録されていた場合にスローされます。
 * <p>
 * S2コンテナからコンポーネントを取得しようとした際に、 指定したキー(コンポーネントのクラス、 インターフェース、
 * あるいは名前)に該当するコンポーネント定義が複数存在した場合、 この例外が発生します。
 * </p>
 * 
 * @see TooManyRegistrationComponentDefImpl::getComponent()
 * 
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.exception
 * @author    klove
 */
namespace seasar::container::exception;
class TooManyRegistrationRuntimeException extends seasar::exception::S2RuntimeException {

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $componentClasses;

    /**
     * <d>S2Container_TooManyRegistrationRuntimeException</d>を構築します。
     * 
     * @param string $key コンポーネントを取得しようとした際に使用したキー
     * @param array $componentClasses
     *            1つのキーに登録された複数コンポーネントのクラスの配列
     */
    public function __construct($key, array $componentClasses) {
        $this->componentClasses = $componentClasses;
        parent::__construct(115, array($key, self::getClassNames($componentClasses)));
    }
    
    /**
     * コンポーネントを取得しようとした際に使用したキーを返します。
     * 
     * @return string コンポーネントを取得するためのキー
     */
    public function getKey() {
        return $this->key;
    }
    
    /**
     * 1つのキーに登録された複数コンポーネントのクラスの配列を返します。
     * 
     * @return array コンポーネントのクラスの配列
     */
    public function getComponentClasses() {
        return $this->componentClasses;
    }

    /**
     * 1つのキーに登録された複数コンポーネントのクラス名の配列を返します。
     *
     * @param array $componentClasses
     * @return array
     */
    private static function getClassNames($componentClasses) {
        $buf = array();
        foreach ($componentClasses as $clazz) {
            if($clazz instanceof ReflectionClass){
                $buf[] = $clazz->getName();
            }else{
                $buf[] = 'class n/a';
            }
        }
        return implode(', ',$buf);
    }
}
