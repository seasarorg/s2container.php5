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
 * ER図を表すクラス
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
class Schema {

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
     * @var array
     */
    private $entities = array();

    /**
     * @var array
     */
    private $relations = array();

    /**
     * @param \seasar\erd\model\Entity $entity
     * @return null
     */
    public function addEntity(\seasar\erd\model\Entity $entity) {
        $entity->setSchema($this);
        $this->entities[] = $entity;
    }

    /**
     * @return array
     */
    public function getEntities() {
        return $this->entities;
    }

    /**
     * @param string $pname
     * @return \seasar\erd\model\Entity
     */
    public function getEntityByPname($pname) {
        foreach($this->entities as $entity) {
            if ($entity->getPname() === $pname) {
                return $entity;
            }
        }
    }

    /**
     * @param string $pname
     * @return \seasar\erd\model\Entity
     */
    public function getEntity($pname) {
        return $this->getEntityByPname($pname);
    }

    /**
     * @param string $lname
     * @return \seasar\erd\model\Entity
     */
    public function getEntityByLname($lname) {
        foreach($this->entities as $entity) {
            if ($entity->getLname() === $lname) {
                return $entity;
            }
        }
    }

    /**
     * @param string $id
     * @return \seasar\erd\model\Entity
     */
    public function getEntityById($id) {
        foreach($this->entities as $entity) {
            if ($entity->getId() === $id) {
                return $entity;
            }
        }
    }

    /**
     * @param \seasar\erd\model\Relation $entity
     * @return null
     */
    public function addRelation(\seasar\erd\model\Relation $relation) {
        $relation->setSchema($this);
        $this->relations[] = $relation;
    }

    /**
     * @return array
     */
    public function getRelations() {
        return $this->relations;
    }

    /**
     * @param string $id
     * @return \seasar\erd\model\Relation
     */
    public function getRelationById($id) {
        foreach($this->relations as $relation) {
            if ($relation->getId() === $id) {
                return $relation;
            }
        }
    }

}
