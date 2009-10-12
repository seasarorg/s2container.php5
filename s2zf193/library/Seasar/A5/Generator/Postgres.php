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
class Seasar_A5_Generator_Postgres extends Seasar_A5_Generator_Abstract {

    /**
     * @see Seasar_A5_Generator_Abstract::getSequenceSrc()
     */
    protected function getSequenceSrc(Seasar_A5_Entity $entity) {
        $pkFields = $entity->getPrimaryKeyFields();
        if (1 == count($pkFields) && 'SERIAL' === $pkFields[0]->getType()) {
            return 'true';
        }
        return 'false';
    }

    /**
     * @see Seasar_A5_Generator_Abstract::getFieldValidator()
     */
    protected function getFieldValidator(Seasar_A5_Field $field) {
        $validators = parent::getFieldValidator($field);
        switch($field->getType()) {
            case 'SERIAL':
                $validators[$field->getType()] = "'Int'";
                break;
        }
        return $validators;
    }
}
