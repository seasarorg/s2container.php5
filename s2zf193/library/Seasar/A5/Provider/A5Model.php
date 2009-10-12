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

use seasar\container\S2ApplicationContext as s2app;

class Seasar_A5_Provider_A5Model extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable {

    /**
     * @param string $dbType
     * @param string $a5erFile
     * @return null
     */
    public function create($dbType = 'postgres', $a5erFile = null) {
        echo PHP_EOL . PHP_EOL;

        $this->_loadProfile();
        if (is_null($a5erFile)) {
            $a5erFile = $this->_loadedProfile->getAttribute('projectDirectory') . '/var/db/project.a5er';
        }
        echo 'A5ER  : ' . $a5erFile . PHP_EOL;

        if ($this->_registry->getRequest()->isPretend()) {
            $dicon = dirname(dirname(__FILE__)) . '/config/' . $dbType . '_pretend.php';
        } else {
            $dicon = dirname(dirname(__FILE__)) . '/config/' . $dbType . '.php';
        }
        require($dicon);
        echo 'DICON : ' . $dicon . PHP_EOL;

        $parser = s2app::get('Seasar_A5_Parser');
        $generator = s2app::get('Seasar_A5_Generator');
        $generator->gen($parser->parse($a5erFile), $this->_loadedProfile->getAttribute('projectDirectory') . '/application/models/DbTable');
    }
}
