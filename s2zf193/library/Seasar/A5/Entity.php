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
 * ER図のEntityを表します。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
 class Seasar_A5_Entity {

    /**
     * テーブル物理名
     * @var string
     */
    private $pname = null;

    /**
     * @param string $pname
     * @return null
     */
    public function setPname($pname) {
        $this->pname = $pname;
    }

    /**
     * @return string
     */
    public function getPname() {
        return $this->pname;
    }

    /**
     * テーブルクラス名
     * @var string
     */
    private $className = null;

    /**
     * @param string $className
     * @return null
     */
    public function setClassName($className) {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * テーブル論理名
     * @var string
     */
    private $lname = null;

    /**
     * @param string $lname
     * @return null
     */
    public function setLname($lname) {
        $this->lname = $lname;
    }

    /**
     * @return string
     */
    public function getLname() {
        return $this->lname;
    }

    /**
     * テーブルのコメント
     * @var string
     */
    private $comment = null;

    /**
     * @param string $comment
     * @return null
     */
    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * テーブルのコメントに含まれるPHPソース
     * @var string
     */
    private $commentedSrc = null;

    /**
     * @param string $commentSrc
     * @return null
     */
    public function setCommentedSrc($commentedSrc) {
        $this->commentedSrc = $commentedSrc;
    }

    /**
     * @return string
     */
    public function getCommentedSrc() {
        return $this->commentedSrc;
    }

    /**
     * テーブルのカラム
     * @var array
     */
    private $fields = array();

    /**
     * @param Seasar_A5_Field $field
     * @return null
     */
    public function addField(Seasar_A5_Field $field) {
        $this->fields[] = $field;
    }

    /**
     * @retrun Seasar_A5_Field $field
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * プライマリキーカラムの物理名を返します。
     * @retrun array
     */
    public function getPrimaryKeys() {
        $pks = array();
        foreach($this->fields as $field) {
            if ($field->isPrimaryKey()) {
                $pks[] = $field->getPname();
            }
        }
        return $pks;
    }

    /**
     * プライマリキーカラムのSeasar_A5_Fieldを返します。
     * @retrun array
     */
    public function getPrimaryKeyFields() {
        $fields = array();
        foreach($this->fields as $field) {
            if ($field->isPrimaryKey()) {
                $fields[] = $field;
            }
        }
        return $fields;
    }

    /**
     * 全Seasar_A5_FieldのPHPソースを返します。
     * @retrun string
     */
    public function getFieldDefSrc() {
        $src = array();
        foreach($this->fields as $field) {
            $src[] = $field->getSource();
        }

        $src = 'array(' . PHP_EOL
             . '        '
             . implode(',' . PHP_EOL . '        ', $src)
             . ')';
        return $src;
    }

}
