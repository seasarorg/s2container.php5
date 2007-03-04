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
 * このインタフェースはプロパティ定義を登録および取得する方法を定義するオブジェクトを表します。
 * <p>
 * プロパティ定義は複数登録することが出来ます。 プロパティ定義の取得はインデックス番号を指定して行います。
 * </p>
 * 
 * @see S2Container_PropertyDef
 *  
 * @copyright  2005-2007 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_PropertyDefAware
{
    /**
     * {@link S2Container_PropertyDef プロパティ定義}を登録(追加)します。
     * 
     * @param S2Container_PropertyDef $propertyDef
     *            プロパティ定義
     */    
    public function addPropertyDef(S2Container_PropertyDef $propertyDef);
    
    /**
     * 登録されている{@link S2Container_PropertyDef プロパティ定義}の数を返します。
     * 
     * @return integer 登録されているプロパティ定義の数
     */
    public function getPropertyDefSize();
    
    /**
     * 指定されたインデックス番号<d>index</d>の{@link S2Container_PropertyDef プロパティ定義}を返します。
     * または、指定したプロパティ名で登録されている{@link PropertyDef プロパティ定義}を返します。
     *  
     * @param integer|string $index
     *            プロパティ定義を指定するインデックス番号、プロパティ名
     * @return S2Container_PropertyDef プロパティ定義
     */
    public function getPropertyDef($index);
    
    /**
     * 指定したプロパティ名にプロパティ定義があれば<d>true</d>を返します。
     * 
     * @param string propertyName プロパティ定義
     * @return boolean プロパティ定義が存在していれば<d>true</d>、存在していなければ<d>false</d>
     */
    public function hasPropertyDef($propertyName);
}
?>
