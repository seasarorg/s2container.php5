<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * diconファイル(xml形式)からS2コンテナを構築します。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar::container::factory;
final class XmlS2ContainerBuilder implements S2ContainerBuilder {

    /**
     * @see seasar::container::factory::S2ContainerBuilder::includeChild()
     */
    public function includeChild(seasar::container::S2Container $parent, $path) {
        $child = $this->build($path);
        $parent->includeChild($child);
        return $child;
    }

    /**
     * @see seasar::container::factory::S2ContainerBuilder::build()
     */
    public function build($path) {
        $rootNode = $this->loadDicon($path);

        $container = new seasar::container::impl::S2ContainerImpl();
        $container->setPath($path);

        $namespace = trim((string)$rootNode['namespace']);
        if ($namespace !== '') {
            $container->setNamespace($namespace); 
        }

        $this->setupInclude($container, $rootNode);
        $this->setupComponentDef($container, $rootNode);
        $this->setupMetaDef($container, $container, $rootNode);

        return $container;
    }

    /**
     * ダイコンファイルをSimpleXmlオブジェクトとして読み込みます。
     *
     * @param string $path ダイコンファイルのパス
     * @throw seasar::exception::FileNotFoundException
     * @throw seasar::exception::DOMException
     * @return SimpleXMLElement
     */
    private function loadDicon($path) {
        if (!is_file($path)) {
            throw new seasar::exception::FileNotFoundException($path);
        }
        if (seasar::container::Config::$DOM_VALIDATE) {
            $dom = new DomDocument();
            $dom->validateOnParse = true;
            if (!$dom->load($path)) {
                throw new seasar::exception::DOMException($path);
            }
            $root = simplexml_import_dom($dom);
        } else {
            $root = simplexml_load_file($path);
        }
        return $root;
    }

    /**
     * ダイコンファイルのincludeタグについてセットアップを実施します。
     * includeタグのpath属性で指定されたファイルが存在しない場合は、expressionとして処理します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $root
     */
    private function setupInclude(seasar::container::S2Container $container, SimpleXMLElement $root){
        foreach ($root->include as $index => $val) {
            $path = trim((string)$val['path']);
            if (!is_file($path)) {
                $path = seasar::util::EvalUtil::formatExecute($path);
            }
            $child = S2ContainerFactory::includeChild($container, $path);
            $child->setRoot($container->getRoot());
        }
    }

    /**
     * ダイコンファイルで定義されているすべてのcomponentタグについてComponentDefを生成します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $root
     * @return array
     */
    private function createComponentDef(seasar::container::S2Container $container, SimpleXMLElement $root) {
        $componentNodes = $root->xpath('//component');
        foreach ($componentNodes as $index => $componentNode) {
            $className       = trim((string)$componentNode['class']);
            $name            = trim((string)$componentNode['name']);
            $componentDef = new seasar::container::impl::ComponentDefImpl($className, $name);
            $container->register($componentDef);
            $instanceMode    = trim((string)$componentNode['instance']);
            $autoBindingMode = trim((string)$componentNode['autoBinding']);
            if ($instanceMode !== '') {
                $componentDef->setInstanceDef(seasar::container::deployer::InstanceDefFactory::getInstanceDef($instanceMode));
            }
            if ($autoBindingMode !== '') {
                $componentDef->setAutoBindingDef(seasar::container::assembler::AutoBindingDefFactory::getAutoBindingDef($autoBindingMode));
            }
        }
        return $componentNodes;
    }

    /**
     * 各コンポーネントについて、セットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $root
     */
    private function setupComponentDef(seasar::container::S2Container $container, $root) {
        $componentNodes = $this->createComponentDef($container, $root);

        $o = count($componentNodes);
        for($i=0; $i<$o; $i++) {
            $componentDef  = $container->getComponentDef($i);
            $componentNode = $componentNodes[$i];
            foreach ($componentNode->arg as $index => $val) {
                $componentDef->addArgDef($this->setupArgDef($container, $val));
            }
            foreach ($componentNode->property as $index => $val) {
                $componentDef->addPropertyDef($this->setupPropertyDef($container, $val));
            }
            foreach ($componentNode->initMethod as $index => $val) {
                $componentDef->addInitMethodDef($this->setupInitMethodDef($container, $val));
            }
            foreach ($componentNode->aspect as $index => $val) {
                $componentDef->addAspectDef($this->setupAspectDef($container, $val, trim((string)$componentNode['class'])));
            }
            $this->setupMetaDef($container, $componentDef, $componentNode);
        }
    }

    /**
     * 各コンポーネントのArgDef、MetaDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $argNode
     */
    private function setupArgDef(seasar::container::S2Container $container, SimpleXMLElement $argNode) {
        $argDef = new seasar::container::impl::ArgDef();
        $this->setupArgDefInternal($container, $argDef, $argNode);
        $this->setupMetaDef($container, $argDef, $argNode);
        return $argDef;
    }

    /**
     * 各コンポーネントのArgDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param seasar::container::impl::ArgDef $argDef
     * @param SimpleXMLElement $argNode
     */
    private function setupArgDefInternal(seasar::container::S2Container $container, seasar::container::impl::ArgDef $argDef, SimpleXMLElement $argNode) {
        if (isset($argNode->component[0])) {
            $componentNode = $argNode->component[0];
            $name            = trim((string)$componentNode['name']);
            $className       = trim((string)$componentNode['class']);
            if ($name !== '') {
                $childComponentDef = $container->getComponentDef($name);
            } else {
                $childComponentDef = $container->getComponentDef($className);
            }
            $argDef->setChildComponentDef($childComponentDef);
        } else {
            $argValue = trim((string)$argNode);
            $injectValue = $this->getInjectValue($argValue);
            if ($injectValue === null){
                if ($container->hasComponentDef($argValue)) {
                    $argDef->setChildComponentDef($container->getComponentDef($argValue));
                } else {
                    $argDef->setExpression($argValue);
                }
            }else{
                $argDef->setValue($injectValue);
            }
        }
    }

    /**
     * コンポーネントのPropertyDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $propertNode
     */
    private function setupPropertyDef(seasar::container::S2Container $container, SimpleXMLElement $propertyNode) {
        $name = (string)$propertyNode['name'];
        $propertyDef = new seasar::container::impl::PropertyDef($name);
        $this->setupArgDefInternal($container, $propertyDef, $propertyNode);
        $this->setupMetaDef($container, $propertyDef, $propertyNode);
        return $propertyDef;
    }

    /**
     * コンポーネントのInitMethodDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $initMethod
     */
    private function setupInitMethodDef(seasar::container::S2Container $container, SimpleXMLElement $initMethod) {
        $name = (string)$initMethod['name'];
        $initMethodDef = new seasar::container::impl::InitMethodDef($name);
        $this->setupMethodDef($container, $initMethod, $initMethodDef);
        return $initMethodDef;
    }

    /**
     * コンポーネントのMethodDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $method
     * @param seasar::container::MethodDef $methodDef
     */
    private function setupMethodDef(seasar::container::S2Container $container, SimpleXMLElement $method, seasar::container::MethodDef $methodDef) {
        $expression = trim((string)$method);
        if ($expression !== '') {
            $methodDef->setExpression($expression);
        }
        foreach ($method->arg as $index => $val) {
            $methodDef->addArgDef($this->setupArgDef($container, $val));
        }
    }

    /**
     * コンポーネントのAspectDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param SimpleXMLElement $aspectNode
     * @param string $targetClassName
     */
    private function setupAspectDef(seasar::container::S2Container $container, SimpleXMLElement $aspectNode, $targetClassName) {
        $pointcut = trim((string)$aspectNode['pointcut']);

        if ($pointcut === '') {
            $pointcut = new seasar::aop::Pointcut(new ReflectionClass($targetClassName));
        } else {
            $pointcuts = split(",", $pointcut);
            $pointcut = new seasar::aop::Pointcut($pointcuts);
        }

        $aspectDef = new seasar::container::impl::AspectDef($pointcut);
        if (isset($aspectNode->component[0])) {
            $componentNode = $aspectNode->component[0];
            $name            = trim((string)$componentNode['name']);
            $className       = trim((string)$componentNode['class']);
            if ($name !== '') {
                $childComponentDef = $container->getComponentDef($name);
            } else {
                $childComponentDef = $container->getComponentDef($className);
            }
            $aspectDef->setChildComponentDef($childComponentDef);
        } else {
            $aspectValue = trim((string)$aspectNode);
            $injectValue = $this->getInjectValue($aspectValue);
            if ($injectValue !== null){
                 $aspectValue = $injectValue;
            }
            if ($container->hasComponentDef($aspectValue)) {
                $aspectDef->setChildComponentDef($container->getComponentDef($aspectValue));
            } else {
                $aspectDef->setExpression($aspectValue);
            }
        }
        return $aspectDef;
    }

    /**
     * コンポーネントのMetaDefについてセットアップを実施します。
     *
     * @param seasar::container::S2Container $container
     * @param object $parentDef
     * @param SimpleXMLElement $parentNode
     */
    private function setupMetaDef(seasar::container::S2Container $container, $parentDef, SimpleXMLElement $parentNode) {
        foreach ($parentNode->meta as $index => $val) {
            $name = trim((string)$val['name']);
            $metaDef = new seasar::container::impl::MetaDef($name);
            $this->setupArgDefInternal($container, $metaDef, $val);
            $parentDef->addMetaDef($metaDef);
        }
    }

    /**
     * argタグやpropertyタグのボディに設定された値について、クォーテーションで囲まれている場合は
     * クォーテーションを取り除いて返します。クォーテーションで囲まれていない場合は、expressionとして
     * 扱うため null を返します。
     *
     * @param string $value
     * @return mixed
     */
    private function getInjectValue($value) {
        $matches = array();
        if (preg_match("/^\"(.*)\"$/", $value, $matches) or
            preg_match("/^\'(.*)\'$/", $value, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

