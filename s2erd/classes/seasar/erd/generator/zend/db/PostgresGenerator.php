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
 * @package   seasar\erd\generator\zend\db
 * @author    klove
 */
namespace seasar\erd\generator\zend\db;
class PostgresGenerator extends AbstractGenerator {

    /**
     * @see \seasar\erd\generator\zend\db\AbstractGenerator::getSequenceSrc()
     */
    protected function getSequenceSrc(\seasar\erd\model\Entity $entity) {
        $pkFields = $entity->getPrimaryKeyFields();
        if (1 == count($pkFields) && 'SERIAL' === $pkFields[0]->getType()) {
            return 'true';
        }
        return parent::getSequenceSrc($entity);
    }

    /**
     * @see \seasar\erd\generator\zend\db\AbstractGenerator::getFieldValidator()
     */
    protected function getFieldValidatorSrc(\seasar\erd\model\Field $field) {
        $validators = parent::getFieldValidatorSrc($field);
        switch($field->getType()) {
            case 'SERIAL':
                $validators[] = "'Digits'";
                break;
        }
        return $validators;
    }
}
