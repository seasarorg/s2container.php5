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
 * ER Masterファイルのパースクラス
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\parser
 * @author    klove
 */
namespace seasar\erd\parser;
class ErmParser extends AbstractParser {

    /**
     * @return \seasar\erd\model\Schema
     */
    public function parse() {
        $schema = new \seasar\erd\model\Schema;
        $schemaNode = simplexml_load_file($this->erdFile);
        $this->parseSchema($schema, $schemaNode);
        return $schema;
    }

    /**
     * @param \seasar\erd\model\Schema $schemaModel
     * @param \SimpleXMLElement $schemaNode
     * @return null
     */
    private function parseSchema(\seasar\erd\model\Schema $schemaModel, \SimpleXMLElement $schemaNode) {
        $tableNodes = $schemaNode->xpath('//contents/table');
        foreach($tableNodes as $tableNode) {
            $schemaModel->addEntity($this->parseTable($schemaNode, $tableNode));
        }

        $colMap = array();
        foreach($schemaModel->getEntities() as $entityModel) {
            foreach($entityModel->getFields() as $fieldModel) {
                $colMap[$fieldModel->getId()] = array($entityModel, $fieldModel);
            }
        }

        foreach($tableNodes as $tableNode) {
            $fieldNodes = $tableNode->xpath('columns/normal_column[foreign_key="true"]');
            $selfTableModel = $schemaModel->getEntityById((string)$tableNode->id);
            foreach($fieldNodes as $fieldNode) {
                $relationModel = $schemaModel->getRelationById((string)$fieldNode->relation);
                if (is_null($relationModel)) {
                    $relationModel = new \seasar\erd\model\Relation;
                    $relationModel->setId((string)$fieldNode->relation);
                    $schemaModel->addRelation($relationModel);
                }

                $relationModel->setEntity2($selfTableModel);
                $fkFieldModel = $selfTableModel->getFieldById((string)$fieldNode->id);
                $fkFieldModel->setForeignKey();
                $relationModel->addField2($fkFieldModel);

                list($refTableModel, $refFieldModel) = $colMap[(string)$fieldNode->referenced_column];
                $relationModel->setEntity1($refTableModel);
                $relationModel->addField1($refFieldModel);
            }
        }

    }

    /**
     * @param \SimpleXMLElement $tableNode
     * @return \seasar\erd\model\Entity
     */
    private function parseTable(\SimpleXMLElement $schemaNode, \SimpleXMLElement $tableNode) {
        $entityModel = new \seasar\erd\model\Entity;
        $entityModel->setId((string)$tableNode->id);
        $entityModel->setPname((string)$tableNode->physical_name);
        $entityModel->setLname((string)$tableNode->logical_name);
        list($comment, $phpSrc) = $this->explodeComment((string)$tableNode->description);
        $entityModel->setComment($comment);
        $entityModel->setCommentedSrc($phpSrc);

        $colNodes = $tableNode->xpath('columns/normal_column');
        foreach($colNodes as $colNode) {
            $entityModel->addField($this->parseCol($schemaNode, $colNode));
        }

        $colNodes = $tableNode->xpath('columns/normal_column[primary_key="true"]');
        foreach($colNodes as $colNode) {
            $fieldModel = $entityModel->getFieldById((string)$colNode->id);
            $fieldModel->setPrimaryKey();
            $entityModel->addPrimaryKeyField($fieldModel);
        }

        return $entityModel;
    }

    /**
     * @param \SimpleXMLElement $schemaNode
     * @param \SimpleXMLElement $colNode
     * @return \SimpleXMLElement|null
     */
    private function getWordNode(\SimpleXMLElement $schemaNode, \SimpleXMLElement $colNode) {
        if (is_numeric((string)$colNode->word_id)) {
            $nodeArray = $schemaNode->xpath('//dictionary/word[id="' . (string)$colNode->word_id . '"]');
            return $nodeArray[0];
        } else if (is_numeric((string)$colNode->referenced_column)) {
            $nodeArray = $schemaNode->xpath('//normal_column[id="' . (string)$colNode->referenced_column . '"]');
            return $this->getWordNode($schemaNode, $nodeArray[0]);
        }
    }

    /**
     * @param \SimpleXMLElement $colNode
     * @return \seasar\erd\model\Field
     */
    private function parseCol(\SimpleXMLElement $schemaNode, \SimpleXMLElement $colNode) {
        $fieldModel = new \seasar\erd\model\Field;
        $fieldModel->setId((string)$colNode->id);

        $wordNode = $this->getWordNode($schemaNode, $colNode);
        if (!is_null($wordNode)) {
            $fieldModel->setPname((string)$wordNode->physical_name);
            $fieldModel->setLname((string)$wordNode->logical_name);
            $fieldModel->setType(strtoupper((string)$wordNode->type));
            $length = (string)$wordNode->length;
            $fieldModel->setLength(is_numeric($length) ? (int)$length : null);
            list($comment, $phpSrc) = $this->explodeComment((string)$wordNode->description);
            $fieldModel->setComment($comment);
            if ($phpSrc === '') {
                $fieldModel->setCommentedSrc('null');
           } else {
                $fieldModel->setCommentedSrc(trim($phpSrc));
            }
        }

        if ((string)$colNode->physical_name !== '') {
            $fieldModel->setPname((string)$colNode->physical_name);
        }

        if ((string)$colNode->logical_name !== '') {
            $fieldModel->setLname((string)$colNode->logical_name);
        }

        if ((string)$colNode->type !== '') {
            $fieldModel->setType(strtoupper((string)$colNode->type));
        }

        $fieldModel->setAutoIncrement((string)$colNode->auto_increment === 'true' ? true : false);
        $fieldModel->setNotNull((string)$colNode->not_null === 'true' ? true : false);
        $fieldModel->setDefault((string)$colNode->default_value);

        if ((string)$colNode->description !== '') {
            list($comment, $phpSrc) = $this->explodeComment((string)$colNode->description);
            $fieldModel->setComment($comment);
            if ($phpSrc === '') {
                $fieldModel->setCommentedSrc('null');
            } else {
                $fieldModel->setCommentedSrc(trim($phpSrc));
            }
        }

        return $fieldModel;
    }
}
