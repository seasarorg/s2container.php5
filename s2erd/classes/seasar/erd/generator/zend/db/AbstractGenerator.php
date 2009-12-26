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
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\generator\zend\db
 * @author    klove
 */
namespace seasar\erd\generator\zend\db;
class AbstractGenerator implements \seasar\erd\Generator {

    /**
     * @var string
     */
    protected $modelClassTpl = null;

    /**
     * @var string
     */
    protected $modelSuperClassTpl = null;

    /**
     * @var string
     */
    protected $modelClassTplFile = null;

    /**
     * @var string
     */
    protected $modelSuperClassTplFile = null;

    /**
     * @var string
     */
    protected $saveDir = null;

    /**
     * @param string $saveDir
     * @return null
     */
    public function setSaveDir($saveDir) {
        $this->saveDir = $saveDir;
    }

    /**
     * @return string
     */
    public function getSaveDir() {
        return $this->saveDir;
    }

    /**
     * @var \seasar\erd\Writer
     */
    protected $writer = null;

    /**
     * @see \seasar\erd\Generator::setWriter()
     */
    public function setWriter(\seasar\erd\Writer $writer) {
        $this->writer = $writer;
    }

    /**
     * @return \seasar\erd\Writer
     */
    public function getWriter() {
        return $this->writer;
    }

    /**
     * @param string $file
     * @return null
     */
    public function setModelSuperClassTplFile($file) {
        $this->modelSuperClassTplFile = $file;
        $this->modelSuperClassTpl = file_get_contents($this->modelSuperClassTplFile);
    }

    /**
     * @param string $file
     * @return null
     */
    public function setModelClassTplFile($file) {
        $this->modelClassTplFile = $file;
        $this->modelClassTpl = file_get_contents($this->modelClassTplFile);
    }

    /**
     * @see \seasar\erd\Generator::generate()
     */
    public function generate(\seasar\erd\model\Schema $schema, \seasar\erd\model\Entity $entity = null) {
        $this->generateModelSuperClass();

        if (is_null($entity)) {
            foreach($schema->getEntities() as $entity) {
                $this->generateModelClass($schema, $entity);
            }
        } else {
            $this->generateModelClass($schema, $entity);
        }
    }

    /**
     * @see \seasar\erd\Generator::generateModelSuperClass()
     */
    public function generateModelSuperClass() {
        $this->writer->setClassName(\seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . \seasar\erd\Config::$MODEL_SUPER_CLASS_NAME);
        $this->writer->setFilePath($this->saveDir . '/' . \seasar\erd\Config::$MODEL_SUPER_CLASS_NAME . '.php');
        $this->writer->write($this->getModelSuperClassSrc());
    }

    /**
     * @see \seasar\erd\Generator::generateModelClass()
     */
    public function generateModelClass(\seasar\erd\model\Schema $schema, \seasar\erd\model\Entity $entity) {
        $this->writer->setClassName(\seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . $this->getClassNameByPname($entity->getPname()));
        $this->writer->setFilePath($this->saveDir . '/' . $this->getClassNameByPname($entity->getPname()) . '.php');
        $this->writer->write($this->getModelClassSrc($schema, $entity));
    }

    /**
     * @return string
     */
    protected function getModelSuperClassSrc() {
        return preg_replace('/@@SUPER_CLASS@@/', \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . \seasar\erd\Config::$MODEL_SUPER_CLASS_NAME, $this->modelSuperClassTpl);
    }

    /**
     * @param \seasar\erd\model\Schema $schema
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getModelClassSrc(\seasar\erd\model\Schema $schema, \seasar\erd\model\Entity $entity) {
        $src = preg_replace('/@@CLASS_NAME@@/', \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . $this->getClassNameByPname($entity->getPname()), $this->modelClassTpl);
        $src = preg_replace('/@@SUPER_CLASS@@/', \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . \seasar\erd\Config::$MODEL_SUPER_CLASS_NAME, $src);

        $src = preg_replace('/@@TABLE_NAME@@/', "'{$entity->getPname()}'", $src);
        $src = preg_replace('/@@PNAME@@/', "'{$entity->getPname()}'", $src);
        $src = preg_replace('/@@LNAME@@/', "'{$entity->getLname()}'", $src);

        $src = preg_replace('/@@COMMENT@@/', "'{$entity->getComment()}'", $src);
        $src = preg_replace('/@@COMMENT_PHP_SOURCE@@/', $entity->getCommentedSrc(), $src);

        $src = preg_replace('/@@PRIMARY_KEYS@@/', $this->getPrimaryKeysSrc($entity), $src);

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
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getSequenceSrc(\seasar\erd\model\Entity $entity) {
        return 'true';
    }

    /**
     * Zend_Db_Table_Abstract::$_primaryのPHPソースを生成する
     *
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getPrimaryKeysSrc(\seasar\erd\model\Entity $entity) {
        $pks = $entity->getPrimaryKeys();
        if (0 < count($pks)) {
            return "array('" . strtolower(implode("', '", $entity->getPrimaryKeys())) . "')";
        } else {
            return 'array()';
        }
    }

    /**
     * Zend_Db_Table_Abstract::$_dependentのPHPソースを生成する
     *
     * @param \seasar\erd\model\Schema $schema
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getDependentSrc(\seasar\erd\model\Schema $schema, \seasar\erd\model\Entity $entity) {
        $dependents = array();
        foreach($schema->getRelations() as $relation) {
            if ($relation->getEntity1()->getPname() === $entity->getPname()) {
                $dependents[] = \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . $this->getClassNameByPname($relation->getEntity2()->getPname());
            }
        }
        if (0 < count($dependents)) {
            $dependents = array_unique($dependents);
            sort($dependents);
            return "array('" . implode("', '", $dependents) . "')";
        } else {
            return "array()";
        }
    }

    /**
     * Zend_Db_Table_Abstract::$_referenceMapのPHPソースを生成する
     *
     * @param \seasar\erd\model\Schema $schema
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getReferenceSrc(\seasar\erd\model\Schema $schema, \seasar\erd\model\Entity $entity) {
        $rules = array();
        foreach($schema->getRelations() as $relation) {
            if ($relation->getEntity2()->getPname() === $entity->getPname()) {
                $refClassName = $this->getClassNameByPname($relation->getEntity1()->getPname());
                $refEntity = \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_' . $refClassName;
                $fields2Pnames = array_map(function($field){return $field->getPname();}, $relation->getFields2());
                sort($fields2Pnames);
                $columns = implode("', '", $fields2Pnames);
                $fields1Pnames = array_map(function($field){return $field->getPname();}, $relation->getFields1());
                sort($fields1Pnames);
                $refCols = implode("', '", $fields1Pnames);
                $ruleName = $this->getClassNameByPname(implode('_', $fields2Pnames));
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
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getEntityFiltersSrc(\seasar\erd\model\Entity $entity) {
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
     * @param \seasar\erd\model\Field $field
     * @return array
     */
    protected function getFieldFilterSrc(\seasar\erd\model\Field $field) {

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
     * @param \seasar\erd\model\Entity $entity
     * @return string
     */
    protected function getEntityValidatorsSrc(\seasar\erd\model\Entity $entity) {
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
     * @param \seasar\erd\model\Field $field
     * @return array
     */
    protected function getFieldValidatorSrc(\seasar\erd\model\Field $field) {

        $validators = array();
        
        if ($field->isNotNull()) {
            $validators[] = "'presence' => 'required'";
        } else {
            $validators[] = "'allowEmpty' => 'true'";
        }

        $type = $field->getType();

        if(preg_match('/^INT/', $type)) {
            $validators[] = "'Int'";
        } else if($type === 'FLOAT' || $type === 'DOUBLE' || $type === 'DECIMAL') {
            $validators[] = "'Float'";
        } else if(preg_match('/CHAR/', $type)) {
            $length = $field->getLength();
            if (!is_null($length)) {
                $validators[] = "array('StringLength', 0, $length, '" . \seasar\erd\Config::$ENCODING . "')";
            }
        } else if($type === 'DATE') {
            $validators[] = "array('Date', 'YYYY-MM-dd')";
        } else if($type === 'TIME') {
            $validators[] = "array('Date', 'HH::mm::ss')";
        } else if($type === 'DATETIME') {
            $validators[] = "array('Date', 'YYYY-MM-dd HH::mm::ss')";
        }

        return $validators;
    }

    /**
     * テーブル名からクラス名を生成する。
     *
     * @param string $tableName
     * @return string
     */
    public static function getClassNameByPname($tableName) {
        $className = preg_replace('/\s/', '', ucwords(preg_replace('/_/', ' ', $tableName)));
        return $className;
    }
}
