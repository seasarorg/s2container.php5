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

require_once(dirname(dirname(__FILE__)) . '/A5.php');
require_once('Zend/Tool/Framework/Manifest/ProviderManifestable.php');
require_once('Zend/Tool/Framework/Provider/Pretendable.php');
require_once('Zend/Tool/Project/Provider/Abstract.php');
require_once('Zend/Loader/Autoloader/Interface.php');

require_once(S2A5_ROOT . '/Schema.php');
require_once(S2A5_ROOT . '/Entity.php');
require_once(S2A5_ROOT . '/Field.php');
require_once(S2A5_ROOT . '/Relation.php');
require_once(S2A5_ROOT . '/Parser.php');
require_once(S2A5_ROOT . '/Writer.php');
require_once(S2A5_ROOT . '/Autoloader.php');
require_once(S2A5_ROOT . '/Generator.php');
require_once(S2A5_ROOT . '/Generator/Abstract.php');
require_once(S2A5_ROOT . '/Provider/A5Model.php');

/**
 * A5用のZend_Tool Manifest
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.2
 * @package   Seasar_A5
 * @author    klove
 */
class Seasar_A5_Manifest implements Zend_Tool_Framework_Manifest_ProviderManifestable {
    
    /**
     * @see Zend_Tool_Framework_Manifest_ProviderManifestable::getProviders()
     */
    public function getProviders() {
        return array(new Seasar_A5_Provider_A5Model);
    }
}
