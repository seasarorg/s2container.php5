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
 * A5Model用のオートローダ
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Autoloader implements Zend_Loader_Autoloader_Interface {

    /**
     * @var Seasar_A5_Schema
     */
    private $schema = null;

    /**
     * @var Seasar_A5_Schema
     */
    private $generator = null;

    /**
     * @param Seasar_A5_Generator $generator
     * @return null
     */
    public function setGenerator(Seasar_A5_Generator $generator) {
        $this->generator = $generator;
    }

    /**
     * @var Seasar_A5_Schema
     */
    private $parser = null;
    
    /**
     * @param Seasar_A5_Parser $parser
     * @return null
     */
    public function setParser(Seasar_A5_Parser $parser) {
        $this->parser = $parser;
    }

    /**
     * @var string
     */
    private $a5erFile = null;

    /**
     * @param string $a5erFile
     * @return null
     */
    public function setA5erFile($a5erFile) {
        $this->a5erFile = $a5erFile;
    }

    /**
     * @param string $className
     * @return boolean
     */
    public function autoload($className) {
        if (0 === strpos($className, S2A5_MODEL_PACKAGE . '_')) {
            $entity = $this->getSchema()->getEntityByClass(substr($className, strlen(S2A5_MODEL_PACKAGE) + 1));
            if (!is_null($entity)) {
                $this->generator->genModelSuperClass();
                $this->generator->genModelClass($this->schema, $entity);
                return true;
            }
        }
    }

    /**
     * @return Seasar_A5_Schema
     */
    private function getSchema() {
        if (is_null($this->schema)) {
            if (is_null($this->a5erFile)) {
                $this->a5erFile = dirname(APPLICATION_PATH) . '/var/db/project.a5er';
            }
            $this->schema = $this->parser->parse($this->a5erFile);
        }
        return $this->schema;
    }

}
