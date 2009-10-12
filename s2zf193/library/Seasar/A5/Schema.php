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
 * ER}‚ð•\‚·ƒNƒ‰ƒX
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Schema {

    /**
     * @var array
     */
    private $entities = array();

    /**
     * @var array
     */
    private $relations = array();

    /**
     * @param Seasar_A5_Entity $entity
     * @return null
     */
    public function addEntity(Seasar_A5_Entity $entity) {
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
     * @return Seasar_A5_Entity
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
     * @return Seasar_A5_Entity
     */
    public function getEntity($pname) {
        return $this->getEntityByPname($pname);
    }

    /**
     * @param string $className
     * @return Seasar_A5_Entity
     */
    public function getEntityByClass($className) {
        foreach($this->entities as $entity) {
            if ($entity->getClassName() === $className) {
                return $entity;
            }
        }
    }

    /**
     * @param string $lname
     * @return Seasar_A5_Entity
     */
    public function getEntityByLname($lname) {
        foreach($this->entities as $entity) {
            if ($entity->getLname() === $lname) {
                return $entity;
            }
        }
    }

    /**
     * @param Seasar_A5_Relation $entity
     * @return null
     */
    public function addRelation(Seasar_A5_Relation $relation) {
        $this->relations[] = $relation;
    }

    /**
     * @return array
     */
    public function getRelations() {
        return $this->relations;
    }
}
