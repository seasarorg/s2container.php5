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
 * MySQL Workbenchファイルのパースクラス
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
class MwbParser extends AbstractParser {

    /**
     * @var string
     */
    private $schemaName = null;

    /**
     * @param string $schemaName
     * @return null
     */
    public function setSchemaName($schemaName) {
        $this->schemaName = $schemaName;
    }

    /**
     * @return \seasar\erd\model\Schema
     */
    public function parse() {
        $schema = new \seasar\erd\model\Schema;
        if ('mwb' === pathinfo($this->erdFile, PATHINFO_EXTENSION)) {
            $root = simplexml_load_file('zip://' . $this->erdFile . '#document.mwb.xml');
        } else {
            $root = simplexml_load_file($this->erdFile);
        }

        $schemaNode = $this->getSchemaNode($root);
        if (is_null($schemaNode)) {
            throw new \Exception("schema [{$this->schemaName}] not found.");
        }

        $this->parseSchema($schema, $schemaNode);

        return $schema;
    }

    /**
     * @param \SimpleXMLElement $root
     * @return null|\SimpleXMLElement
     */
    private function getSchemaNode(\SimpleXMLElement $root) {
        $nodeArray = $root->xpath('//value[@key="schemata"]');
        foreach($nodeArray[0]->children() as $schema) {
            $nameArray = $schema->xpath('value[@key="name"]');
            if ((string)$nameArray[0] === $this->schemaName) {
                return $schema;
            }
        }
        return null;
    }

    /**
     * @param \seasar\erd\model\Schema $schemaModel
     * @param \SimpleXMLElement $schemaNode
     * @return null
     */
    private function parseSchema(\seasar\erd\model\Schema $schemaModel, \SimpleXMLElement $schemaNode) {
        $nodeArray = $schemaNode->xpath('value[@key="tables"]');
        foreach($nodeArray[0]->children() as $tableNode) {
            $schemaModel->addEntity($this->parseTAble($tableNode));
        }

        $nodeArray = $schemaNode->xpath('value[@key="tables"]/*/value[@key="foreignKeys"]');
        foreach($nodeArray as $tableFkNode) {
            foreach($tableFkNode->children() as $fkNode) {
                $relationModel = new \seasar\erd\model\Relation;
                $schemaModel->addRelation($relationModel);
                $refTableNodeArray = $fkNode->xpath('link[@key="referencedTable"]');
                $refTableModel = $schemaModel->getEntityById((string)$refTableNodeArray[0]);
                $relationModel->setEntity1($refTableModel);

                $refColNodeArray = $fkNode->xpath('value[@key="referencedColumns"]');
                foreach($refColNodeArray[0]->children() as $refColNode) {
                    $relationModel->addField1($refTableModel->getFieldById((string)$refColNode));
                }

                $refTableNodeArray = $fkNode->xpath('link[@key="owner"]');
                $refTableModel = $schemaModel->getEntityById((string)$refTableNodeArray[0]);
                $relationModel->setEntity2($refTableModel);

                $refColNodeArray = $fkNode->xpath('value[@key="columns"]');
                foreach($refColNodeArray[0]->children() as $refColNode) {
                    $fkFieldModel = $refTableModel->getFieldById((string)$refColNode);
                    $fkFieldModel->setForeignKey();
                    $relationModel->addField2($fkFieldModel);
                }
            }
        }

    }

    /**
     * @param \SimpleXMLElement $tableNode
     * @return \seasar\erd\model\Entity
     */
    private function parseTable(\SimpleXMLElement $tableNode) {
        $entityModel = new \seasar\erd\model\Entity;
        $entityModel->setId((string)$tableNode['id']);
        
        $nodeArray = $tableNode->xpath('value[@key="name"]');
        $entityModel->setPname((string)$nodeArray[0]);
        $entityModel->setLname((string)$nodeArray[0]);
        //$entityModel->setClassName($this->getClassName((string)$nodeArray[0]));

        $nodeArray = $tableNode->xpath('value[@key="comment"]');
        list($comment, $phpSrc) = $this->explodeComment((string)$nodeArray[0]);
        $entityModel->setComment($comment);
        $entityModel->setCommentedSrc($phpSrc);

        $nodeArray = $tableNode->xpath('value[@key="columns"]');
        foreach($nodeArray[0]->children() as $colNode) {
            $entityModel->addField($this->parseCol($colNode));
        }

        $pkIndexNode = $this->getPrimaryIndexNode($tableNode);
        if (!is_null($pkIndexNode)) {
            $nodeArray = $pkIndexNode->xpath('value[@key="columns"]');
            foreach($nodeArray[0]->children() as $indexColNode) {
                $nodeArray = $indexColNode->xpath('link[@key="referencedColumn"]');
                $fieldModel = $entityModel->getFieldById((string)$nodeArray[0]);
                $fieldModel->setPrimaryKey();
                $entityModel->addPrimaryKeyField($fieldModel);
            }
        }
        

        return $entityModel;
    }

    /**
     * @param \SimpleXMLElement $tableNode
     * @return \SimpleXMLElement
     */
    private function getPrimaryIndexNode(\SimpleXMLElement $tableNode) {
        $nodeArray = $tableNode->xpath('value[@key="indices"]');
        foreach($nodeArray[0]->children() as $indexNode) {
            $nodeArray = $indexNode->xpath('value[@key="isPrimary"]');
            if ((string)$nodeArray[0] === '1') {
                return $indexNode;
            }
        }
        return null;
    }

    /**
     * @param \SimpleXMLElement $colNode
     * @return \seasar\erd\model\Field
     */
    private function parseCol(\SimpleXMLElement $colNode) {
        $fieldModel = new \seasar\erd\model\Field;
        $fieldModel->setId((string)$colNode['id']);

        $nodes = $colNode->xpath('value[@key="name"]');
        $fieldModel->setPname((string)$nodes[0]);
        $fieldModel->setLname((string)$nodes[0]);

        $nodes = $colNode->xpath('link[@key="simpleType"]');
        $items = explode('.', (string)$nodes[0]);
        $fieldModel->setType(strtoupper(array_pop($items)));

        $nodes = $colNode->xpath('value[@key="length"]');
        $fieldModel->setLength((string)$nodes[0]);

        $nodes = $colNode->xpath('value[@key="precision"]');
        $fieldModel->setPrecision((string)$nodes[0]);

        $nodes = $colNode->xpath('value[@key="scale"]');
        $fieldModel->setScale((string)$nodes[0]);

        $nodes = $colNode->xpath('value[@key="autoIncrement"]');
        if ((string)$nodes[0] === '1') {
            $fieldModel->setTypeOption('auto_increment');
        }

        $nodes = $colNode->xpath('value[@key="isNotNull"]');
        $fieldModel->setNotNull((string)$nodes[0] === '1' ? true : false);

        $nodes = $colNode->xpath('value[@key="defaultValue"]');
        $fieldModel->setDefault((string)$nodes[0]);

        $nodes = $colNode->xpath('value[@key="comment"]');
        list($comment, $phpSrc) = $this->explodeComment((string)$nodes[0]);
        $fieldModel->setComment($comment);
        if ($phpSrc === '') {
            $fieldModel->setCommentedSrc('null');
        } else {
            $fieldModel->setCommentedSrc(trim($phpSrc));
        }
        return $fieldModel;
    }
}
