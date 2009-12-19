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
 * ER図のリレーションを表すクラス
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\model
 * @author    klove
 */
namespace seasar\erd\model;
class Relation {

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
     * リレーション物理名
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
     * Parent側のEntity、参照される側、FK先テーブル
     * @var \seasar\erd\model\Entity
     */ 
    private $entity1 = null;

    /**
     * @param \seasar\erd\model\Entity $entity1
     * @return null
     */
    public function setEntity1(\seasar\erd\model\Entity $entity1) {
        $this->entity1 = $entity1;
    }

    /**
     * @return \seasar\erd\model\Entity
     */
    public function getEntity1() {
        return $this->entity1;
    }

    /**
     * Child側のEntity、参照する側、FKを持つテーブル
     * @var \seasar\erd\model\Entity
     */ 
    private $entity2 = null;

    /**
     * @param \seasar\erd\model\Entity $entity2
     * @return null
     */
    public function setEntity2(\seasar\erd\model\Entity $entity2) {
        $this->entity2 = $entity2;
    }

    /**
     * @return \seasar\erd\model\Entity
     */
    public function getEntity2() {
        return $this->entity2;
    }

    /**
     * @var array
     */ 
    private $fields1 = array();

    /**
     * @param \seasar\erd\model\Field $field1
     * @return null
     */
    public function addField1(\seasar\erd\model\Field $field1) {
        $this->fields1[] = $field1;
    }

    /**
     * @return array
     */
    public function getFields1() {
        return $this->fields1;
    }

    /**
     * @var array
     */ 
    private $fields2 = array();

    /**
     * @param \seasar\erd\model\Field $field2
     * @return null
     */
    public function addField2(\seasar\erd\model\Field $field2) {
        $this->fields2[] = $field2;
    }

    /**
     * @return array
     */
    public function getFields2() {
        return $this->fields2;
    }
}
