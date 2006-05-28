<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_MetaDefSupport
{
    private $metaDefs_ = array();

    private $container_;

    /**
     * @param S2Container
     */
    public function __construct($container = null)
    {
        if ($container instanceof S2Container) {
            $this->setContainer($container);
        }
    }

    /**
     * @param S2Container_MetaDef
     */
    public function addMetaDef(S2Container_MetaDef $metaDef)
    {
        if ($this->container_ != null) {
            $metaDef->setContainer($this->container_);
        }
        $this->metaDefs_[] = $metaDef;
    }

    /**
     * @return int
     */
    public function getMetaDefSize()
    {
        return count($this->metaDefs_);
    }

    /**
     * @return S2Container_MetaDef
     */
    public function getMetaDef($name)
    {
        if (is_int($name)) {
            return $this->metaDefs_[$name];
        }
        $o = $this->getMetaDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                return $metaDef;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getMetaDefs($name)
    {
        $defs = array();
        $o = $this->getMetaDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                $defs[] = $metaDef;
            }
        }
        return $defs;
    }

    /**
     * @param S2Container
     */
    public function setContainer(S2Container $container)
    {
        $this->container_ = $container;
        $o = $this->getMetaDefSize();
        for ($i = 0; $i < $o; ++$i) {
            $this->getMetaDef($i)->setContainer($container);
        }
    }
}
?>
