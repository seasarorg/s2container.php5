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
 * A5ERファイルのパースクラス
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\parser
 * @author    klove
 */
namespace seasar\erd\parser;
class A5Parser extends AbstractParser {

    /**
     * @return \seasar\erd\model\Schema
     */
    public function parse() {
        $schema = new \seasar\erd\model\Schema;

        $contents = file($this->erdFile);
        $entity = null;
        $relation = null;
        $relations = array();
        foreach ($contents as $line) {
            $line = mb_convert_encoding(trim($line), \seasar\erd\Config::$ENCODING, 'sjis-win');

            if ($line === '[Entity]') {
                $entity = new \seasar\erd\model\Entity;
                $schema->addEntity($entity);
                $relation = null;
            }

            if ($line === '[Relation]') {
                $entity = null;
                $relation = new \stdClass;
                $relations[] = $relation;
            }

            $matches = array();
            if (preg_match('/^PName=(.+)$/', $line, $matches)) {
                if (is_null($entity)) {
                    $relation->pname = $matches[1];
                } else {
                    $entity->setPname($matches[1]);
                }
            }

            $matches = array();
            if (preg_match('/^LName=(.+)$/', $line, $matches)) {
                $entity->setLname($matches[1]);
            }

            $matches = array();
            if (preg_match('/^Comment=(.+)$/', $line, $matches) && !is_null($entity)) {
                list($comment, $phpSrc) = $this->explodeComment($matches[1]);
                $entity->setComment($comment);
                $entity->setCommentedSrc($phpSrc);
            }

            $matches = array();
            if (preg_match('/^Field=(.+)$/', $line, $matches)) {
                $this->createField($entity, $matches[1]);
            }

            $matches = array();
            if (preg_match('/^Entity1=(.+)$/', $line, $matches)) {
                $relation->entity1 = $matches[1];
            }

            $matches = array();
            if (preg_match('/^Entity2=(.+)$/', $line, $matches)) {
                $relation->entity2 = $matches[1];
            }

            $matches = array();
            if (preg_match('/^Fields1=(.+)$/', $line, $matches)) {
                $relation->fields1 = explode(',', $matches[1]);
            }

            $matches = array();
            if (preg_match('/^Fields2=(.+)$/', $line, $matches)) {
                $relation->fields2 = explode(',', $matches[1]);
            }

        }

        foreach($relations as $relation) {
            $relationModel = new \seasar\erd\model\Relation;
            $schema->addRelation($relationModel);
            $entity1Model = $schema->getEntityByPname($relation->entity1);
            $relationModel->setEntity1($entity1Model);
            foreach($relation->fields1 as $field) {
                $relationModel->addField1($entity1Model->getFieldByPname($field));
            }
            $entity2Model = $schema->getEntityByPname($relation->entity2);
            $relationModel->setEntity2($entity2Model);
            foreach($relation->fields2 as $field) {
                $fieldModel = $entity2Model->getFieldByPname($field);
                $fieldModel->setForeignKey();
                $relationModel->addField2($fieldModel);
            }
        }
        return $schema;
    }

    /**
     * Field="社員ID","employee_id","SERIAL","NOT NULL",0,"","",$FFFFFFFF
     *
     * @param \seasar\erd\model\Entity $entityModel
     * @param string $fieldLine
     * @return \seasar\erd\model\Field
     */
    private function createField(\seasar\erd\model\Entity $entityModel, $fieldLine) {
        $field = new \seasar\erd\model\Field;
        $entityModel->addField($field);

        $fieldLine = preg_replace('/,\$.+?$/', '', $fieldLine);
        $items = preg_split('/,/',  $fieldLine, 7);
        foreach($items as &$item) {
            $item = preg_replace('/^"/', '', $item);
            $item = preg_replace('/"$/', '', $item);
        }
        $field->setLname($items[0]);
        $field->setPname($items[1]);
        $types = preg_split('/\s+/', $items[2], 2);
        if (count($types) < 2) {
            $types[] = null;
        }
        $field->setType(preg_replace('/\(\d+\)$/', '', preg_replace('/^@/', '', $types[0])));
        $matches = array();
        if (preg_match('/\((\d+)\)/', $types[0], $matches)) {
            $field->setLength($matches[1]);
        }
        $field->setTypeOption($types[1]);
        $field->setNotNull($items[3] === 'NOT NULL' ? true : false);

        if ($items[4] != '') {
            $field->setPrimaryKey();
            $entityModel->addPrimaryKeyField($field);
        }

        $field->setDefault($items[5]);

        list($comment, $phpSrc) = $this->explodeComment($items[6]);
        $field->setComment($comment);
        if ($phpSrc === '') {
            $field->setCommentedSrc('null');
        } else {
            $field->setCommentedSrc(trim($phpSrc));
        }
        return $field;
    }

    /**
     * コメントに埋め込まれたPHPソースを切り出す。
     *
     * @param string $comment
     * @return array
     */
    public function explodeComment($comment) {
        $matches = array();
        if (preg_match('/<\?php(.+?)\?>/', $comment, $matches)) {
            $src = str_replace('\n', PHP_EOL, $matches[1]);
            $src = str_replace('\q', "'", $src);
            $src = str_replace('\Q', '"', $src);
            return array(preg_replace('/<\?php(.+?)\?>/', '', $comment), $src);
        }
        return array($comment, '');
    }
}
