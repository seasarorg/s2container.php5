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
// $Id:$
/**
 * 配列のkeyに存在しないインデックス番号が指定された場合に<b>throw</b>される例外クラスです。
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.1.2
 * @package    org.seasar.framework.exception
 * @author     klove
 */
final class S2Container_IndexOutOfBoundsException extends Exception
{
    /**
     * @param integer $outOfIndex 配列のkeyに存在しないインデックス番号
     */
    public function __construct($outOfIndex)
    {
        parent::__construct(__CLASS__ . " : Out of index <$outOfIndex>");
    }
  
}
?>
