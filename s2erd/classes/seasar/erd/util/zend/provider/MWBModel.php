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
// | governing pmwbissions and limitations under the License.             |
// +----------------------------------------------------------------------+
/**
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\zf\provider
 * @author    klove
 */

//namespace seasar\erd\util\zend\provider;

use seasar\container\S2ApplicationContext as s2app;

class Seasar_Erd_MWBModel extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable {
    const DEFAULT_MWBER_FILE = '<project/var/db/project.mwb>';
    /**
     * @param string $schema
     * @param string $mwbFile
     * @return null
     */
    public function create($schema = 'mydb', $mwbFile = self::DEFAULT_MWBER_FILE) {
        $this->_registry->getResponse()->appendContent('');
        $this->_registry->getResponse()->appendContent('');

        $this->_loadProfile();
        $projectPath = $this->_loadedProfile->getAttribute('projectDirectory');
        if ($mwbFile === self::DEFAULT_MWBER_FILE) {
            $mwbFile = $projectPath . '/var/db/project.mwb';
        }
        $this->_registry->getResponse()->appendContent("MySQL Workbench File:\t" . $mwbFile);
        $this->_registry->getResponse()->appendContent('');

        if (!defined('APPLICATION_PATH')) {
            define('APPLICATION_PATH', $projectPath . '/application');
        }

        s2app::init();
        if ($this->_registry->getRequest()->isPretend()) {
            s2component('seasar\erd\writer\StdoutWriter');
        } else {
            s2component('seasar\erd\writer\FileWriter');
        }
        require_once($projectPath . '/application/configs/s2erd.php');
        $parser = s2app::get('seasar\erd\Parser');
        if (!$parser instanceof \seasar\erd\parser\MwbParser) {
            $this->_registry->getResponse()->appendContent("!!! ERROR:\tMwbParser not registered. Please see application/configs/s2erd.php file.");
            return;
        }
        $parser->setSchemaName($schema);

        $parser->setErdFile($mwbFile);
        $generator = s2app::get('seasar\erd\Generator');
        $generator->generate($parser->parse());
    }
}
