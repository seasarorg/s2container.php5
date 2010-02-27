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
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\zf\provider
 * @author    klove
 */

//namespace seasar\erd\util\zend\provider;

use seasar\container\S2ApplicationContext as s2app;

class Seasar_Erd_Util_Zend_Provider_A5Model extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable {
    const DEFAULT_A5ER_FILE = '<project/var/db/project.a5er>';
    /**
     * @param string $a5erFile
     * @return null
     */
    public function create($a5erFile = self::DEFAULT_A5ER_FILE) {
        $this->_registry->getResponse()->appendContent('');
        $this->_registry->getResponse()->appendContent('');

        $this->_loadProfile();
        $projectPath = $this->_loadedProfile->getAttribute('projectDirectory');
        if ($a5erFile === self::DEFAULT_A5ER_FILE) {
            $a5erFile = $projectPath . '/var/db/project.a5er';
        }
        $this->_registry->getResponse()->appendContent("A5ER File:\t" . $a5erFile);
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
        if (!$parser instanceof \seasar\erd\parser\A5Parser) {
            $this->_registry->getResponse()->appendContent("!!! ERROR:\tA5Parser not registered. Please see application/configs/s2erd.php file.");
            return;
        }

        $parser->setErdFile($a5erFile);
        $generator = s2app::get('seasar\erd\Generator');
        $generator->generate($parser->parse());
    }
}
