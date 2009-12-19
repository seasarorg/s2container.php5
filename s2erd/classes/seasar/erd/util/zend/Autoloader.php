<?php
// +---------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
// +---------------------------------------------------------------------+
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
// +---------------------------------------------------------------------+
/**
 * ZF用のオートローダ
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\zend
 * @author    klove
 */
namespace seasar\erd\util\zend;
require_once('Zend/Loader/Autoloader/Interface.php');

class Autoloader implements \Zend_Loader_Autoloader_Interface {

    /**
     * @var \seasar\erd\model\Schema
     */
    private $schema = null;

    /**
     * @var \seasar\erd\Generator
     */
    private $generator = null;

    /**
     * @param \seasar\erd\Generator $generator
     * @return null
     */
    public function setGenerator(\seasar\erd\Generator $generator) {
        $this->generator = $generator;
    }

    /**
     * @var \seasar\erd\Parser
     */
    private $parser = null;
    
    /**
     * @param \seasar\erd\Parser $parser
     * @return null
     */
    public function setParser(\seasar\erd\Parser $parser) {
        $this->parser = $parser;
    }

    /**
     * @param string $className
     * @return boolean
     */
    public function autoload($className) {
        if (0 === strpos($className, \seasar\erd\Config::$MODEL_PACKAGE_NAME . '_')) {
            $targetClassName = substr($className, strlen(\seasar\erd\Config::$MODEL_PACKAGE_NAME) + 1);
            foreach($this->getSchema()->getEntities() as $entity) {
                $tableClassName = \seasar\erd\generator\zend\db\AbstractGenerator::getClassNameByPname($entity->getPname());
                if ($targetClassName === $tableClassName) {
                    $this->generator->generate($this->schema, $entity);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return \seasar\erd\model\Schema
     */
    private function getSchema() {
        if (is_null($this->schema)) {
            $this->schema = $this->parser->parse();
        }
        return $this->schema;
    }
}
