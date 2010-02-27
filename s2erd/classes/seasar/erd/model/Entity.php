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
 * ER図のEntityを表します。
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\model
 * @author    klove
 */
namespace seasar\erd\model;
class Entity {

    /**
     * ID
     * @var mixed
     */
    private $id = null;

    /**
     * @param string $id
     * @return null
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Schema
     * @var \seasar\erd\model\Schema
     */
    private $schema = null;

    /**
     * @param string $schema
     * @return null
     */
    public function setSchema(\seasar\erd\model\Schema $schema) {
        $this->schema = $schema;
    }

    /**
     * @return mixed
     */
    public function getSchema() {
        return $this->schema;
    }

    /**
     * @param mixed $id
     * @retrun \seasar\erd\model\Field
     */
    public function getFieldById($id) {
        foreach($this->fields as $field) {
            if($field->getId() === $id) {
                return $field;
            }
        }
        return null;
    }

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
     * @param mixed $pname
     * @retrun \seasar\erd\model\Field
     */
    public function getFieldByPname($pname) {
        foreach($this->fields as $field) {
            if($field->getPname() === $pname) {
                return $field;
            }
        }
        return null;
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
     * @param mixed $lname
     * @retrun \seasar\erd\model\Field
     */
    public function getFieldByLname($lname) {
        foreach($this->fields as $field) {
            if($field->getLname() === $lname) {
                return $field;
            }
        }
        return null;
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
     * @param \seasar\erd\model\Field $field
     * @return null
     */
    public function addField(\seasar\erd\model\Field $field) {
        $field->setEntity($this);
        $this->fields[] = $field;
    }

    /**
     * @retrun \seasar\erd\model\Field $field
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * PKフィールドカラム
     * @var array
     */
    private $primaryKeyFields = array();

    /**
     * プライマリキーカラムの物理名を返します。
     * @retrun array
     */
    public function getPrimaryKeys() {
        $pks = array();
        foreach($this->primaryKeyFields as $field) {
            $pks[] = $field->getPname();
        }
        return $pks;
    }

    /**
     * プライマリキーカラムの\seasar\erd\model\Fieldを返します。
     * @retrun array
     */
    public function getPrimaryKeyFields() {
        return $this->primaryKeyFields;
    }

    /**
     * @param \seasar\erd\model\Field $field
     * @retrun null
     */
    public function addPrimaryKeyField(\seasar\erd\model\Field $field) {
        $this->primaryKeyFields[] = $field;
    }

    /**
     * 全\seasar\erd\model\FieldのPHPソースを返します。
     * @retrun string
     */
    public function getFieldDefSrc() {
        $src = array();
        foreach($this->fields as $field) {
            $src []= "        '{$field->getPname()}' => {$field->getSource()}";
        }
        $src = 'array(' . PHP_EOL . implode(',' . PHP_EOL, $src) . ')';
        return $src;
    }

}
