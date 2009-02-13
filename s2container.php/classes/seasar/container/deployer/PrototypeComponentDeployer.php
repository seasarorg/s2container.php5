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
 * prototype用のComponentDeployerです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.deployer
 * @author    klove
 */
namespace seasar\container\deployer;
class PrototypeComponentDeployer extends AbstractComponentDeployer {

    /**
     * @var boolean
     */
    private $instantiating = false;

    /**
     * PrototypeComponentDeployerを構築します。
     *
     * @param \seasar\container\ComponentDef $componentDef
     */
    public function __construct(\seasar\container\ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see \seasar\container\deployer\AbstractComponentDeployer::deploy()
     */
    public function deploy() {
        if ($this->instantiating) {
            throw new \seasar\container\exception\CyclicInstantiationRuntimeException($this->getComponentDef()->getComponentClass());
        }
        $this->instantiating = true;
        $component = $this->getConstructorAssembler()->assemble();
        $this->getPropertyAssembler()->assemble($component);
        $this->getInitMethodAssembler()->assemble($component);
        $this->instantiating = false;
        return $component;
    }
}

