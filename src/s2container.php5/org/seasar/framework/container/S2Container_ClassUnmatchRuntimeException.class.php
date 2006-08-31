<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * コンポーネントのインスタンスを、 {@link S2Container_ComponentDef コンポーネント定義}に指定されたクラスにキャスト出来ない場合にスローされます。
 * <p>
 * {@link  S2Container_ComponentDef::setExpression()}でインスタンスの生成を定義している場合は、
 * そのインスタンスをコンポーネント定義に指定されたクラスにキャスト出来ないことを表します。
 * </p>
 * <p>
 * 外部コンポーネントを{@link S2Container::injectDependency()}などでインジェクションする場合は、
 * そのコンポーネントを、 コンポーネント定義に指定されたクラスにキャストできないことを表します。
 * </p>
 * 
 * @see S2Container_ConstructorAssembler::assemble()
 * @see S2Container::injectDependency()
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
class S2Container_ClassUnmatchRuntimeException
    extends S2Container_S2RuntimeException
{
    /**
     * @var ReflectionClass
     */
    private $componentClass_;

    /**
     * @var ReflectionClass
     */
    private $realComponentClass_;
    
    /**
     * <b>ClassUnmatchRuntimeException</b>を構築します。
     * 
     * @param ReflectionClass $componentClass
     *            コンポーネント定義に指定されたクラス
     * @param ReflectionClass $realComponentClass
     *            コンポーネントの実際の型
     */
    public function __construct($componentClass,
                                $realComponentClass)
    {
        parent::__construct("ESSR0069", 
            array($componentClass != null ? 
                      $componentClass->getName() : "null",
                  $realComponentClass != null ? 
                      $realComponentClass->getName() : "null"));
            
        $this->componentClass_ = $componentClass;
        $this->realComponentClass_ = $realComponentClass;
    }
    
    /**
     * コンポーネント定義に指定されたクラスを返します。
     * 
     * @return ReflectionClass コンポーネント定義に指定されたクラス
     */
    public function getComponentClass()
    {
        return $ths->componentClass_;
    }
    
    /**
     * コンポーネントの実際の型を返します。
     * 
     * @return ReflectionClass コンポーネントの実際の型
     */
    public function getRealComponentClass()
    {
        return $this->realComponentClass_;
    }
}
?>
