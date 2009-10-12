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
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Parser {

    /**
     * @param string $a5erFile
     * @return Seasar_A5_Schema
     */
    public function parse($a5erFile) {
        $schema = new Seasar_A5_Schema;

        $contents = file($a5erFile);
        $entity = null;
        $relation = null;
        foreach ($contents as $line) {
            $line = mb_convert_encoding(trim($line), S2A5_ENCODING, 'SJIS');

            if ($line === '[Entity]') {
                $entity = new Seasar_A5_Entity;
                $schema->addEntity($entity);
                $relation = null;
            }

            if ($line === '[Relation]') {
                $relation = new Seasar_A5_Relation;
                $schema->addRelation($relation);
                $entity = null;
            }

            $matches = array();
            if (preg_match('/^PName=(.+)$/', $line, $matches)) {
                if (is_null($entity)) {
                    $relation->setPname($matches[1]);
                } else {
                    $entity->setPname($matches[1]);
                    $entity->setClassName($this->getClassName($matches[1]));
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
                $entity->addField($this->createField($matches[1]));
            }

            $matches = array();
            if (preg_match('/^Entity1=(.+)$/', $line, $matches)) {
                $relation->setEntity1($matches[1]);
                $relation->setClassName1($this->getClassName($matches[1]));
            }

            $matches = array();
            if (preg_match('/^Entity2=(.+)$/', $line, $matches)) {
                $relation->setEntity2($matches[1]);
                $relation->setClassName2($this->getClassName($matches[1]));
            }

            $matches = array();
            if (preg_match('/^Fields1=(.+)$/', $line, $matches)) {
                $relation->setFields1($matches[1]);
                $relation->setCamelizeFields1($this->getClassName(implode('_', $relation->getFields1())));
            }

            $matches = array();
            if (preg_match('/^Fields2=(.+)$/', $line, $matches)) {
                $relation->setFields2($matches[1]);
                $relation->setCamelizeFields2($this->getClassName(implode('_', $relation->getFields2())));
            }

        }
        return $schema;
    }

    /**
     * Field="社員ID","employee_id","SERIAL","NOT NULL",0,"","",$FFFFFFFF
     *
     * @param string $fieldLine
     * @return Seasar_A5_Field
     */
    private function createField($fieldLine) {
        $field = new Seasar_A5_Field;
        $fieldLine = preg_replace('/,\$.+?$/', '', $fieldLine);
        $items = preg_split('/,/',  $fieldLine, 7);
        foreach($items as &$item) {
            $item = preg_replace('/^"/', '', $item);
            $item = preg_replace('/"$/', '', $item);
        }
        $field->setLname($items[0]);
        $field->setPname($items[1]);
        $field->setType(preg_replace('/\(\d+\)$/', '', preg_replace('/^@/', '', $items[2])));
        $matches = array();
        if (preg_match('/\((\d+)\)/', $items[2], $matches)) {
            $field->setSize($matches[1]);
        }
        $field->setNotNull($items[3] === 'NOT NULL' ? true : false);

        if ($items[4] == '') {
            $field->setPrimaryKey(false);
        } else {
            $field->setPrimaryKey(true);
            $field->setPrimaryKeyIndex((integer)$items[4]);
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

    /**
     * テーブル名からクラス名を生成する。
     *
     * @param string $tableName
     * @return string
     */
    public function getClassName($tableName) {
        $className = preg_replace('/\s/', '', ucwords(preg_replace('/_/', ' ', $tableName)));
        return $className;
    }

}
