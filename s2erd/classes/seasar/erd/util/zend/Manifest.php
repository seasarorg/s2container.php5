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

//namespace seasar\erd\util\zend;

require_once('Zend/Tool/Framework/Manifest/ProviderManifestable.php');
require_once('Zend/Tool/Framework/Provider/Pretendable.php');
require_once('Zend/Tool/Project/Provider/Abstract.php');

require_once(dirname(__FILE__) . '/provider/A5Model.php');
require_once(dirname(__FILE__) . '/provider/ERMModel.php');
require_once(dirname(__FILE__) . '/provider/MWBModel.php');
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/S2Erd.php');

/**
 * A5用のZend_Tool Manifest
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 0.2.0
 * @package   seasar\erd\util\zend
 * @author    klove
 */
class Seasar_Erd_Manifest implements Zend_Tool_Framework_Manifest_ProviderManifestable {
    
    /**
     * @see Zend_Tool_Framework_Manifest_ProviderManifestable::getProviders()
     */
    public function getProviders() {
        //return array(new \seasar\erd\util\zend\provider\A5Model);
        return array(new Seasar_Erd_A5Model, new Seasar_Erd_ERMModel, new Seasar_Erd_MWBModel);
    }
}
