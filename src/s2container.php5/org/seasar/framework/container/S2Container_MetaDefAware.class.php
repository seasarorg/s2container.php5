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
 * このインターフェースは、メタデータ定義を登録および取得することのできるオブジェクトを表します。 
 * <p>
 * メタデータ定義は複数登録することができます。 <br>
 * 登録されているメタデータ定義は、
 * <ul>
 * <li>メタデータ定義定義名</li>
 * <li>インデックス番号</li>
 * </ul>
 * を指定して取得することができます。
 * </p>
 * 
 * @see S2Container_MetaDef
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
interface S2Container_MetaDefAware
{
    /**
     * メタデータ定義を登録（追加）します。
     * 
     * @param S2Container_MetaDef $metaDef メタデータ定義
     */    
    public function addMetaDef(S2Container_MetaDef $metaDef);
    
    /**
     * 登録されているメタデータ定義の数を返します。
     * 
     * @return integer 登録されているメタデータ定義の数
     */
    public function getMetaDefSize();
    
    /**
     * インデックス番号<d>index</d>で指定されたメタデータ定義を返します。
     * <p>
     * インデックス番号は、0, 1, 2…の順に採番されます。
     * </p>
     * 
     * または、指定したメタデータ定義名で登録されているメタデータ定義を取得します。 <br>
     * メタデータ定義が登録されていない場合、<d>null</d>を返します。
     * 
     * @param integer|string $index メタデータ定義を指定するインデックス番号 | メタデータ定義名
     * @return S2Container_MetaDef メタデータ定義
     */
    public function getMetaDef($index);
    
    /**
     * 指定したメタデータ定義名で登録されているメタデータ定義を取得します。 <br>
     * メタデータ定義が登録されていない場合、要素数0の配列を返します。
     * @param string $name メタデータ定義名
     * @return array メタデータ定義を格納した配列
     */
    public function getMetaDefs($name);
}
?>
