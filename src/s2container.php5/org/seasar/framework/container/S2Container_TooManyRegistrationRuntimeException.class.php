<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * 1つのキーに複数のコンポーネントが登録されていた場合にスローされます。
 * <p>
 * S2コンテナからコンポーネントを取得しようとした際に、 指定したキー(コンポーネントのクラス、 インターフェース、
 * あるいは名前)に該当するコンポーネント定義が複数存在した場合、 この例外が発生します。
 * </p>
 * 
 * @see S2Container_TooManyRegistrationComponentDefImpl::getComponent()
 * 
 * @copyright  2005-2007 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
final class S2Container_TooManyRegistrationRuntimeException
    extends S2Container_S2RuntimeException
{
    private $key_;
    private $componentClasses_;

    /**
     * <d>S2Container_TooManyRegistrationRuntimeException</d>を構築します。
     * 
     * @param string $key コンポーネントを取得しようとした際に使用したキー
     * @param array $componentClasses
     *            1つのキーに登録された複数コンポーネントのクラスの配列
     */
    public function __construct($key,$componentClasses)
    {
        $args[] = $key;
        $args[] = self::getClassNames($componentClasses);
        parent::__construct("ESSR0045",$args);
        $this->componentClasses_ = $componentClasses;
    }
    
    /**
     * コンポーネントを取得しようとした際に使用したキーを返します。
     * 
     * @return string コンポーネントを取得するためのキー
     */
    public function getKey()
    {
        return $this->key_;
    }
    
    /**
     * 1つのキーに登録された複数コンポーネントのクラスの配列を返します。
     * 
     * @return array コンポーネントのクラスの配列
     */
    public function getComponentClasses()
    {
        return $this->componentClasses_;
    }

    /**
     * @param array $componentClasses
     * @return array
     */
    private static function getClassNames($componentClasses)
    {
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
?>
