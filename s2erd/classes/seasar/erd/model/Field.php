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
 * ER図のEntityのFieldを表します。
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
class Field {

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
     * Entity
     * @var \seasar\erd\model\Entity
     */
    private $entity = null;

    /**
     * @param string $entity
     * @return null
     */
    public function setEntity(\seasar\erd\model\Entity $entity) {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * フィールド物理名
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
     * フィールド論理名
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
     * データタイプ
     * @var string
     */ 
    private $type = null;

    /**
     * @param boolean $type
     * @return null
     */
    public function setType($type) {
        $this->type = strtoupper($type);
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * データタイプ
     * @var string
     */ 
    private $typeOption = null;

    /**
     * @param boolean $typeOption
     * @return null
     */
    public function setTypeOption($typeOption) {
        $this->typeOption = $typeOption;
    }

    /**
     * @return string
     */
    public function getTypeOption() {
        return $this->typeOption;
    }

    /**
     * データサイズ
     * @var integer
     */ 
    private $length = null;

    /**
     * @param integer $length
     * @return null|integer
     */
    public function setLength($length) {
        $this->length = $length;
    }

    /**
     * @return integer
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * Auto Incrementかどうか
     * @var boolean
     */ 
    private $autoIncrement = false;

    /**
     * @param boolean $autoIncrement
     * @return boolean
     */
    public function setAutoIncrement($autoIncrement = true) {
        $this->autoIncrement = $autoIncrement;
    }

    /**
     * @return boolean
     */
    public function isAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * NOT NULLかどうか
     * @var boolean
     */ 
    private $notNull = false;

    /**
     * @param boolean $notNull
     * @return boolean
     */
    public function setNotNull($notNull = true) {
        $this->notNull = $notNull;
    }

    /**
     * @return boolean
     */
    public function isNotNull() {
        return $this->notNull;
    }

    /**
     * SQL DEFAULT式
     * @var string
     */ 
    private $default = null;

    /**
     * @param string $default
     * @return null
     */
    public function setDefault($default) {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * フィールドコメント
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
     * コメントに埋め込まれたPHPソース
     * @var string
     */ 
    private $commentedSrc = null;

    /**
     * @param string $commentedSrc
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
     * フィールド情報を表すPHPソースを返します。
     * @return string
     */ 
    public function getSource() {

        $src = array();
        $src[] = "'pname' => '{$this->pname}'";
        $src[] = "'lname' => '{$this->lname}'";
        $src[] = "'type' => '{$this->type}'";

        if (is_null($this->typeOption)) {
            $src[] = "'type_opt' => null";
        } else {
            $src[] = "'type_opt' => '{$this->typeOption}'";
        }

        if (is_null($this->length)) {
            $src[] = "'length' => null";
        } else {
            $src[] = "'length' => {$this->length}";
        }

        if (is_null($this->scale)) {
            $src[] = "'scale' => null";
        } else {
            $src[] = "'scale' => {$this->scale}";
        }

        if (is_null($this->precision)) {
            $src[] = "'precision' => null";
        } else {
            $src[] = "'precision' => {$this->precision}";
        }

        if ($this->isNotNull()) {
            $src[] = "'nn' => true";
        } else {
            $src[] = "'nn' => false";
        }

        if ($this->isPrimaryKey()) {
            $src[] = "'pk' => true";
        } else {
            $src[] = "'pk' => false";
        }

        if ($this->isForeignKey()) {
            $src[] = "'fk' => true";
        } else {
            $src[] = "'fk' => false";
        }

        $src[] = "'default' => '{$this->default}'";
        $src[] = "'comment' => '{$this->comment}'";
        $src[] = "'options' => {$this->commentedSrc}";

        $sp = '              ';
        $src = 'array(' . PHP_EOL . $sp . implode(',' . PHP_EOL . $sp, $src) . ')';
        return $src;
    }

    /**
     * PKかどうか
     * @var boolean
     */ 
    private $primaryKey = false;

    /**
     * @param boolean $primaryKey
     * @return boolean
     */
    public function setPrimaryKey($primaryKey = true) {
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return boolean
     */
    public function isPrimaryKey() {
        return $this->primaryKey;
    }

    /**
     * FKかどうか
     * @var boolean
     */ 
    private $foreignKey = false;

    /**
     * @param boolean $foreignKey
     * @return boolean
     */
    public function setForeignKey($foreignKey = true) {
        $this->foreignKey = $foreignKey;
    }

    /**
     * @return boolean
     */
    public function isForeignKey() {
        return $this->foreignKey;
    }

    /**
     * @var string
     */ 
    private $scale = null;

    /**
     * @param string $scale
     * @return null
     */
    public function setScale($scale) {
        $this->scale = $scale;
    }

    /**
     * @return string
     */
    public function getScale() {
        return $this->scale;
    }

    /**
     * @var string
     */ 
    private $precision = null;

    /**
     * @param string $precision
     * @return null
     */
    public function setPrecision($precision) {
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getPrecision() {
        return $this->precision;
    }
}
