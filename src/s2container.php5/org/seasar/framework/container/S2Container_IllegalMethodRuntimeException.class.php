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
 * 不正なメソッド・インジェクション定義が指定されていた場合にスローされます。
 * <p>
 * メソッド・インジェクションを実行した際に、 メソッドの引数として指定されたコンポーネントが見つからない場合や、
 * 引数を適切な型にパース出来ない場合などに発生します。
 * </p>
 * 
 * @see S2Container_MethodDef
 * @see S2Container_InitMethodDef
 * @see S2Container_DestroyMethodDef
 * @see S2Container_MethodAssembler
 * @see S2Container_AbstractMethodAssembler
 * 
 * @copyright  2005-2007 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
class S2Container_IllegalMethodRuntimeException
    extends S2Container_S2RuntimeException
{
    private $componentClass;
    private $methodName;

    /**
     * <code>IllegalMethodRuntimeException</code>を構築します。
     * 
     * @param ReflectionClass $componentClass 不正なメソッド・インジェクション定義を含むコンポーネントのクラス
     * @param string $methodName 不正なメソッド・インジェクション定義のメソッド名
     * @param Exception $cause 原因となった例外
     */
    public function __construct(ReflectionClass $componentClass,
                                $methodName,
                                Exception $cause = null)
    {
        parent::__construct("ESSR0060",
                            array($componentClass->getName(),
                                  $methodName,
                                  $cause),
                            $cause);
        $this->componentClass = $componentClass;
        $this->methodName = $methodName;
    }

    /**
     * 不正なメソッド・インジェクション定義を含むコンポーネントのクラスを返します。
     * 
     * @return ReflectionClass コンポーネントのクラス
     */
    public function getComponentClass()
    {
        return $this->componentClass;
    }
    
    /**
     * 不正なメソッド・インジェクション定義のメソッド名を返します。
     * 
     * @return string メソッド名
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
}
?>
