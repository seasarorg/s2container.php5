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
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Relation {

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
     * Entity名
     * @var string
     */ 
    private $entity1 = null;

    /**
     * @param string $entity1
     * @return null
     */
    public function setEntity1($entity1) {
        $this->entity1 = $entity1;
    }

    /**
     * テーブルクラス名
     * @var string
     */
    private $className1 = null;

    /**
     * @param string $className1
     * @return null
     */
    public function setClassName1($className1) {
        $this->className1 = $className1;
    }

    /**
     * @return string
     */
    public function getClassName1() {
        return $this->className1;
    }

    /**
     * @return string
     */
    public function getEntity1() {
        return $this->entity1;
    }

    /**
     * テーブルクラス名
     * @var string
     */
    private $className2 = null;

    /**
     * @param string $className2
     * @return null
     */
    public function setClassName2($className2) {
        $this->className2 = $className2;
    }

    /**
     * @return string
     */
    public function getClassName2() {
        return $this->className2;
    }

    /**
     * Entity名
     * @var string
     */ 
    private $entity2 = null;

    /**
     * @param string $entity2
     * @return null
     */
    public function setEntity2($entity2) {
        $this->entity2 = $entity2;
    }

    /**
     * @return string
     */
    public function getEntity2() {
        return $this->entity2;
    }

    /**
     * Fields名
     * @var array
     */ 
    private $fields1 = array();

    /**
     * @param string $field1
     * @return null
     */
    public function setFields1($fields1) {
        $this->fields1 = explode(',', $fields1);
    }

    /**
     * @return string
     */
    public function getFields1() {
        return $this->fields1;
    }

    /**
     * Camelize Fields1名
     * @var string
     */ 
    private $camelizeFields1 = null;

    /**
     * @param string $camelizeField1
     * @return null
     */
    public function setCamelizeFields1($camelizeFields1) {
        $this->camelizeFields1 = $camelizeFields1;
    }

    /**
     * @return string
     */
    public function getCamelizeFields1() {
        return $this->camelizeFields1;
    }

    /**
     * Fields2名
     * @var array
     */ 
    private $fields2 = array();

    /**
     * @param string $field2
     * @return null
     */
    public function setFields2($fields2) {
        $this->fields2 = explode(',', $fields2);
    }

    /**
     * @return string
     */
    public function getFields2() {
        return $this->fields2;
    }

    /**
     * Camelize Fields2名
     * @var string
     */ 
    private $camelizeFields2 = null;

    /**
     * @param string $camelizeField2
     * @return null
     */
    public function setCamelizeFields2($camelizeFields2) {
        $this->camelizeFields2 = $camelizeFields2;
    }

    /**
     * @return string
     */
    public function getCamelizeFields2() {
        return $this->camelizeFields2;
    }

    /**
     * @return boolean
     */
    public function isDependent(Seasar_A5_Entity $entity) {
        return $this->entity1 === $entity->getPname();
    }

    /**
     * @return string
     */
    public function getDependentEntity() {
        return $this->entity2;
    }

    /**
     * @return string
     */
    public function getDependentEntityClass() {
        return $this->className2;
    }

    /**
     * @return boolean
     */
    public function isReference(Seasar_A5_Entity $entity) {
        return $this->entity2 === $entity->getPname();
    }
    
    /**
     * @return string
     */
    public function getReferenceEntity() {
        return $this->entity1;
    }

    /**
     * @return string
     */
    public function getReferenceEntityClass() {
        return $this->className1;
    }

}
