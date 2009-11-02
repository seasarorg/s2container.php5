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
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5_Generator
 * @author    klove
 */
class Seasar_A5_Generator_Abstract implements Seasar_A5_Generator {

    /**
     * @var string
     */
    protected $modelClassTpl = null;

    /**
     * @var string
     */
    protected $modelSuperClassTpl = null;

    /**
     * @var Seasar_A5_Writer
     */
    protected $writer = null;

    /**
     * @param Seasar_A5_Writer $writer
     * @return null
     */
    public function setWriter(Seasar_A5_Writer $writer) {
        $this->writer = $writer;
    }

    /**
     * @return Seasar_A5_Writer
     */
    public function getWriter() {
        return $this->writer;
    }

    /**
     * Seasar_A5_Generator_Abstractを構築する。
     */
    public function __construct() {
        $this->modelSuperClassTpl = file_get_contents(dirname(dirname(__FILE__)) . '/tpl/model_abstract.tpl');
        $this->modelClassTpl      = file_get_contents(dirname(dirname(__FILE__)) . '/tpl/model.tpl');
    }

    /**
     * @see Seasar_A5_Generator::gen()
     */
    public function gen(Seasar_A5_Schema $schema, $saveDir = '.') {
        $this->genModelSuperClass($saveDir);

        foreach($schema->getEntities() as $entity) {
            $this->genModelClass($schema, $entity, $saveDir);
        }
    }

    /**
     * @see Seasar_A5_Generator::genModelSuperClass()
     */
    public function genModelSuperClass($saveDir = '.') {
        $this->writer->setClassName(S2A5_MODEL_PACKAGE . '_' . S2A5_MODEL_SUPER_CLASS);
        $this->writer->setResource($saveDir . '/' . S2A5_MODEL_SUPER_CLASS . '.php');
        $this->writer->write($this->getModelSuperClassSrc());
    }

    /**
     * @see Seasar_A5_Generator::genModelClass()
     */
    public function genModelClass(Seasar_A5_Schema $schema, Seasar_A5_Entity $entity, $saveDir = '.') {
        $saveFile = $saveDir . '/' . $entity->getClassName() . '.php';
        $this->writer->setClassName(S2A5_MODEL_PACKAGE . '_' . $entity->getClassName());
        $this->writer->setResource($saveFile);
        $this->writer->write($this->getModelClassSrc($schema, $entity));
    }

    /**
     * @return string
     */
    protected function getModelSuperClassSrc() {
        return preg_replace('/@@SUPER_CLASS@@/', S2A5_MODEL_PACKAGE . '_' . S2A5_MODEL_SUPER_CLASS, $this->modelSuperClassTpl);
    }

    /**
     * @param Seasar_A5_Schema $schema
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getModelClassSrc(Seasar_A5_Schema $schema, Seasar_A5_Entity $entity) {
        $src = preg_replace('/@@CLASS_NAME@@/', S2A5_MODEL_PACKAGE . '_' . $entity->getClassName(), $this->modelClassTpl);
        $src = preg_replace('/@@SUPER_CLASS@@/', S2A5_MODEL_PACKAGE . '_' . S2A5_MODEL_SUPER_CLASS, $src);

        $src = preg_replace('/@@TABLE_NAME@@/', "'{$entity->getPname()}'", $src);
        $src = preg_replace('/@@PNAME@@/', "'{$entity->getPname()}'", $src);
        $src = preg_replace('/@@LNAME@@/', "'{$entity->getLname()}'", $src);

        $src = preg_replace('/@@COMMENT@@/', "'{$entity->getComment()}'", $src);
        $src = preg_replace('/@@COMMENT_PHP_SOURCE@@/', $entity->getCommentedSrc(), $src);

        $pks = $entity->getPrimaryKeys();
        if (0 < count($pks)) {
            $pks = implode("', '", $entity->getPrimaryKeys());
            $src = preg_replace('/@@PRIMARY_KEYS@@/', "array('$pks')", $src);
        } else {
            $src = preg_replace('/@@PRIMARY_KEYS@@/', "array()", $src);
        }

        $src = preg_replace('/@@SEQUENCE@@/', $this->getSequenceSrc($entity), $src);

        $src = preg_replace('/@@FIELDS@@/', $entity->getFieldDefSrc(), $src);

        $src = preg_replace('/@@FILTERS@@/', $this->getEntityFiltersSrc($entity), $src);

        $src = preg_replace('/@@VALIDATORS@@/', $this->getEntityValidatorsSrc($entity), $src);

        $src = preg_replace('/@@DEPENDENT@@/', $this->getDependentSrc($schema, $entity), $src);

        $src = preg_replace('/@@REFERENCE@@/', $this->getReferenceSrc($schema, $entity), $src);

        return $src;
    }

    /**
     * Zend_Db_Table_Abstract::$_sequenceのPHPソースを生成する
     *
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getSequenceSrc(Seasar_A5_Entity $entity) {
        return 'true';
    }

    /**
     * Zend_Db_Table_Abstract::$_dependentのPHPソースを生成する
     *
     * @param Seasar_A5_Schema $schema
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getDependentSrc(Seasar_A5_Schema $schema, Seasar_A5_Entity $entity) {
        $dependents = array();
        foreach($schema->getRelations() as $relation) {
            if ($relation->isDependent($entity)) {
                $dependents[] = S2A5_MODEL_PACKAGE . '_' . $relation->getDependentEntityClass();
            }
        }
        if (0 < count($dependents)) {
            $dependents = array_unique($dependents);
            return "array('" . implode("', '", $dependents) . "')";
        } else {
            return "array()";
        }
    }

    /**
     * Zend_Db_Table_Abstract::$_referenceMapのPHPソースを生成する
     *
     * @param Seasar_A5_Schema $schema
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getReferenceSrc(Seasar_A5_Schema $schema, Seasar_A5_Entity $entity) {
        $rules = array();
        foreach($schema->getRelations() as $relation) {
            if ($relation->isReference($entity)) {
                $refClassName = $relation->getReferenceEntityClass();
                $refEntity = S2A5_MODEL_PACKAGE . '_' . $refClassName;
                $columns = implode("', '", $relation->getFields2());
                $refCols = implode("', '", $relation->getFields1());
                $ruleName = $relation->getCamelizeFields2();
                $ruleSrc = "'$ruleName' => array(" . PHP_EOL
                         . "           'columns' => array('{$columns}')," . PHP_EOL
                         . "           'refTableClass' => '{$refEntity}'," . PHP_EOL
                         . "           'refColumns' => array('{$refCols}'))";
                $rules[] = $ruleSrc;
            }
        }

        if (0 < count($rules)) {
            return "array(" . PHP_EOL . '        ' . implode("," . PHP_EOL . '        ', $rules) . ")";
        } else {
            return "array()";
        }
    }

    /**
     * FilterInput用のFILTERSのPHPソースを生成する
     *
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getEntityFiltersSrc(Seasar_A5_Entity $entity) {
        $src = array();
        foreach($entity->getFields() as $field) {
            $filters = $this->getFieldFilterSrc($field);
            if (!empty($filters)) {
                $src[] = "'{$field->getPname()}' => " . 'array(' . implode(', ', $filters) . ')';
            }
        }

        $src = 'array(' . PHP_EOL
             . '        '
             . implode(',' . PHP_EOL . '        ', $src)
             . ')';
        return $src;
    }

    /**
     * FilterInput用のFiltersのPHPソースを生成する。(カラム)
     *
     * @param Seasar_A5_Field $field
     * @return array
     */
    protected function getFieldFilterSrc(Seasar_A5_Field $field) {

        $filters = array();

        /*
        if (false === strpos($field->getType(), 'CHAR')) {
            $filters['null'] = "'Null'";
        }
        */
        
        switch($field->getType()) {
            case 'INT':
            case 'INTEGER':
                $filters[] = "'Int'";
                break;
        }

        return $filters;
    }

    /**
     * FilterInput用のValidatorsのPHPソースを生成する
     *
     * @param Seasar_A5_Entity $entity
     * @return string
     */
    protected function getEntityValidatorsSrc(Seasar_A5_Entity $entity) {
        $src = array();
        foreach($entity->getFields() as $field) {
            $validators = $this->getFieldValidatorSrc($field);
            $src[] = "'{$field->getPname()}' => " . 'array(' . implode(', ', $validators) . ')';
        }

        $src = 'array(' . PHP_EOL
             . '        '
             . implode(',' . PHP_EOL . '        ', $src)
             . ')';
        return $src;
    }

    /**
     * FilterInput用のValidatorsのPHPソースを生成する。(カラム)
     *
     * @param Seasar_A5_Field $field
     * @return array
     */
    protected function getFieldValidatorSrc(Seasar_A5_Field $field) {

        $validators = array();
        
        if ($field->isNotNull()) {
            $validators[] = "'presence' => 'required'";
        } else {
            $validators[] = "'allowEmpty' => 'true'";
        }

        switch($field->getType()) {
            case 'INT':
            case 'INTEGER':
                $validators[] = "'Int'";
                break;
            case 'FLOAT':
            case 'DECIMAL':
                $validators[] = "'Float'";
                break;
            case 'CHAR':
            case 'VARCHAR':
                $size = $field->getSize();
                if (!is_null($size)) {
                    $validators[] = "array('StringLength', 0, $size, '" . S2A5_ENCODING . "')";
                }
                break;
            case 'DATE':
                $validators[] = "array('Date', 'YYYY-MM-dd')";
                break;
            case 'TIME':
                $validators[] = "array('Date', 'HH::mm::ss')";
                break;
            case 'DATETIME':
                $validators[] = "array('Date', 'YYYY-MM-dd HH::mm::ss')";
                break;
        }

        return $validators;
    }
}
