<?php
final class S2ContainerFactory {
    public static $DTD_PATH;
    public static $BUILDER_CONFIG_PATH;
    private static $builderProps_;
    private static $builders_ = array();
    private static $defaultBuilder_;
    private static $inited_ = false;
    private function S2ContainerFactory() {
        $this->init();
    }
    private static function init(){
        if(!S2ContainerFactory::$inited_){
            S2ContainerFactory::$defaultBuilder_ = new XmlS2ContainerBuilder();
            S2ContainerFactory::$DTD_PATH = S2CONTAINER_PHP5 . "/org/seasar/framework/container/factory/components21.dtd";
            S2ContainerFactory::$BUILDER_CONFIG_PATH = S2CONTAINER_PHP5 . "/S2CntainerBuilder.properties";
            if(is_readable(S2ContainerFactory::$BUILDER_CONFIG_PATH)){
                   S2ContainerFactory::$builderProps_ = parse_ini_file(S2ContainerFactory::$BUILDER_CONFIG_PATH);
            }
            S2ContainerFactory::$builders_['xml'] = S2ContainerFactory::$defaultBuilder_;
            S2ContainerFactory::$builders_['dicon'] = S2ContainerFactory::$defaultBuilder_;
            S2ContainerFactory::$inited_ = true;
        }
    }
    public static function create($path) {
        S2ContainerFactory::init();
        $ext = S2ContainerFactory::getExtension($path);
        $container = S2ContainerFactory::getBuilder($ext)->build($path);
        return $container;
    }
    public static function includeChild(S2Container $parent, $path) {
        S2ContainerFactory::init();
        $root = $parent->getRoot();
        $child = null;
        if ($root->hasDescendant($path)) {
            $child = $root->getDescendant($path);
            $parent->includeChild($child);
        } else {
            $ext = S2ContainerFactory::getExtension($path);
            $builder = S2ContainerFactory::getBuilder($ext);
            $child = $builder->includeChild($parent,$path);
            $root->registerDescendant($child);
        }
        return $child;
    }
    private static function getExtension($path) {
        $filename = basename($path);
        preg_match('/\.([a-zA-Z0-9]+)$/',$filename,$regs);
        return $regs[1];
    }
    private static function getBuilder($ext) {
        $builder = null;
        if(array_key_exists($ext,S2ContainerFactory::$builders_)){
            $builder = S2ContainerFactory::$builders_[$ext];
            if ($builder != null) {
                return $builder;
            }
        }
        $className = S2ContainerFactory::$builderProps_[$ext];
        if ($className != null) {
            $builder = new $className();
            S2ContainerFactory::$builders_[$ext] = $builder;
        } else {
            $builder = S2ContainerFactory::$defaultBuilder_;
        }
        return $builder;
    }
}

interface S2ContainerBuilder {
    public function build($path);
    public function includeChild(S2Container $parent, $path);
}

interface MetaDefAware {
    public function addMetaDef(MetaDef $metaDef);
    public function getMetaDefSize();
    public function getMetaDef($index);
    public function getMetaDefs($name);
}

interface S2Container extends MetaDefAware{
    public function getComponent($componentKey);
    public function injectDependency($outerComponent,$componentName="");
    public function register($component, $componentName="");
    public function getComponentDefSize();
    public function getComponentDef($index);
    public function hasComponentDef($componentKey);
    public function hasDescendant($path);
    public function getDescendant($path);
    public function registerDescendant(S2Container $descendant);
    public function includeChild(S2Container $child);
    public function getChildSize();
    public function getChild($index);
    public function init();
    public function destroy();
    public function getNamespace();
    public function setNamespace($namespace);
    public function getPath();
    public function setPath($path);
    public function getRoot();
    public function setRoot(S2Container $root);
}

class S2ContainerImpl implements S2Container {
    private $componentDefMap_ = array();
    private $componentDefList_ = array();
    private $namespace_;
    private $path_;
    private $children_ = array();
    private $descendants_ = array();
    private $root_;
    private $inited_ = false;
    private $metaDefSupport_;
    public function S2ContainerImpl() {
        $this->metaDefSupport_ = new MetaDefSupport();
        $this->root_ = $this;
        $componentDef = new SimpleComponentDef($this, ContainerConstants::CONTAINER_NAME);
        $this->componentDefMap_[ContainerConstants::CONTAINER_NAME] = $componentDef;
        $this->componentDefMap_['S2Container'] = $componentDef;
    }
    public function getRoot() {
        return $this->root_;
    }
    public function setRoot(S2Container $root) {
        $this->root_ = $root;
    }
    public function getComponent($componentKey) {
        return $this->getComponentDef($componentKey)->getComponent();
    }
    public function injectDependency($outerComponent, $componentName="") {
        if(is_object($outerComponent)){
            if($componentName != ""){
                $this->getComponentDef($componentName)->injectDependency($outerComponent);
            }else{
                $this->getComponentDef(get_class($outerComponent))->injectDependency($outerComponent);
            }
        }
    }
    public function register($component,$componentName="") {
        if($component instanceof ComponentDef){
            $this->register0($component);
            array_push($this->componentDefList_,$component);
        }else if(is_object($component)){
            $this->register(new SimpleComponentDef($component,trim($componentName)));
        }else {
            $this->register(new ComponentDefImpl($component, trim($componentName)));
        }
    }
    private function register0(ComponentDef $componentDef) {
        if ($componentDef->getContainer() == null) {
            $componentDef->setContainer($this);
        }    
        $this->registerByClass($componentDef);
        $this->registerByName($componentDef);
    }
    private function registerByClass(ComponentDef $componentDef) {
        $classes = $this->getAssignableClasses($componentDef->getComponentClass());
        for ($i = 0; $i < count($classes); ++$i) {
            $this->registerMap($classes[$i], $componentDef);
        }
    }
    private function registerByName(ComponentDef $componentDef) {
        $componentName = $componentDef->getComponentName();
        if ($componentName != "") {
            $this->registerMap($componentName, $componentDef);
        }
    }
    private function registerMap($key, ComponentDef $componentDef) {
        if (array_key_exists($key,$this->componentDefMap_)) {
            $this->processTooManyRegistration($key, $componentDef);
        } else {
            $this->componentDefMap_[$key] = $componentDef;
        }
    }
    public function getComponentDefSize() {
        return count($this->componentDefList_);
    }
    public function getComponentDef($key){
        if(is_int($key)){
        	if(!isset($this->componentDefList_[$key])){
        		throw new ComponentNotFoundRuntimeException($key);
        	}
            return $this->componentDefList_[$key];
        }
        if(is_object($key)){
            $key = get_class($key);
        }
        $cd = $this->getComponentDef0($key);
        if ($cd == null) {
            throw new ComponentNotFoundRuntimeException($key);
        }
        return $cd;
    }
    private function getComponentDef0($key) {
        $cd = null;
        if(array_key_exists($key,$this->componentDefMap_)){
            $cd = $this->componentDefMap_[$key];
            if ($cd != null) {
                return $cd;
            }
        }
        if(preg_match("/(.+)\.(.+)/",$key,$ret)){
            if ($this->hasComponentDef($ret[1])) {
                $child = $this->getComponent($ret[1]);
                if ($child->hasComponentDef($ret[2])) {
                    return $child->getComponentDef($ret[2]);
                }
            }
        }
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
            $child = $this->getChild($i);
            if ($child->hasComponentDef($key)) {
                return $child->getComponentDef($key);
            }
        }
        return null;
    }
    public function hasComponentDef($componentKey) {
        return $this->getComponentDef0($componentKey) != null;
    }
    public function hasDescendant($path) {
        return array_key_exists($path,$this->descendants_);
    }
    public function getDescendant($path) {
        $descendant = null;
        if(array_key_exists($path,$this->descendants_)){
            $descendant = $this->descendants_[$path];
        }
        if ($descendant != null) {
            return $descendant;
        } else {
            throw new ContainerNotRegisteredRuntimeException($path);
        }
    }
    public function registerDescendant(S2Container $descendant) {
        $this->descendants_[$descendant->getPath()] = $descendant;
    }
    public function includeChild(S2Container $child) {
        $child->setRoot($this->getRoot());
        array_push($this->children_,$child);
        $ns = $child->getNamespace();
        if ($ns != null) {
            $this->registerMap($ns, new S2ContainerComponentDef($child, $ns));
        }
    }
    public function getChildSize() {
        return count($this->children_);
    }
    public function getChild($index) {
    	if(!isset($this->children_[$index])){
    		throw new ContainerNotRegisteredRuntimeException("Child:".$index);
    	}
        return $this->children_[$index];
    }
    public function init() {
        if ($this->inited_) {
            return;
        }
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
            $this->getChild($i)->init();
        }
        for ($i = 0; $i < $this->getComponentDefSize(); ++$i) {
            $this->getComponentDef($i)->init();
        }
        $this->inited_ = true;
    }
    public function destroy() {
        if (!$this->inited_) {
            return;
        }
        for ($i = $this->getComponentDefSize() - 1; 0 <= $i; --$i) {
            try {
                $this->getComponentDef($i)->destroy();
            } catch (Exception $e) {
                print $e->getMessage() . "\n";
            }
        }
        for ($i = $this->getChildSize() - 1; 0 <= $i; --$i) {
            $this->getChild($i)->destroy();
        }
        $this->inited_ = false;
    }
    public function getNamespace() {
        return $this->namespace_;
    }
    public function setNamespace($namespace) {
        $this->namespace_ = $namespace;
        $this->componentDefMap_[$this->namespace_] = 
            new SimpleComponentDef($this,$this->namespace_);
    }
    public function getPath() {
        return $this->path_;
    }
    public function setPath($path) {
        $this->path_ = $path;
    }
    public function addMetaDef(MetaDef $metaDef) {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    public function getMetaDef($name) {
        return $this->metaDefSupport_->getMetaDef($name);
    }
    public function getMetaDefs($name) {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    public function getMetaDefSize() {
        return $this->metaDefSupport_->getMetaDefSize();
    }
    private static function &getAssignableClasses($componentClass) {
        if(! $componentClass instanceof ReflectionClass){
        	return array();
        }
        $ref = $componentClass;
        $classes = array();
        $interfaces = $ref->getInterfaces();
        for($i=0;$i<count($interfaces);$i++) {
            array_push($classes,$interfaces[$i]->getName());
        }
        while($ref != null){
            array_push($classes,$ref->getName());
            $ref = $ref->getParentClass();
        }
        return $classes;
    }
    private function  processTooManyRegistration($key,
            ComponentDef $componentDef) {
        $cd = $this->componentDefMap_[$key];
        if ($cd instanceof TooManyRegistrationComponentDef) {
            $cd->addComponentClass($componentDef->getComponentClass());
        } else {
            $tmrcf = new TooManyRegistrationComponentDef($key);
            $tmrcf->addComponentClass($cd->getComponentClass());
            $tmrcf->addComponentClass($componentDef->getComponentClass());
            $this->componentDefMap_[$key] = $tmrcf;
        }
    }
}

final class MetaDefSupport {
    private $metaDefs_ = array();
    private $container_;
    public function MetaDefSupport($container=null) {
        if($container instanceof S2Container){
            $this->setContainer($container);
        }
    }
    public function addMetaDef(MetaDef $metaDef) {
        if ($this->container_ != null) {
            $metaDef->setContainer($this->container_);
        }
        array_push($this->metaDefs_,$metaDef);
    }
    public function getMetaDefSize() {
        return count($this->metaDefs_);
    }
    public function getMetaDef($name) {
        if(is_int($name)){
            return $this->metaDefs_[$name];
        }
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                return $metaDef;
            }
        }
        return null;
    }
    public function getMetaDefs($name) {
        $defs = array();
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                array_push($defs,$metaDef);
            }
        }
        return $defs;
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $this->getMetaDef($i)->setContainer($container);
        }
    }
}

interface ArgDefAware {
    public function addArgDef(ArgDef $argDef);
    public function getArgDefSize();
    public function getArgDef($index);
}

interface PropertyDefAware {
    public function addPropertyDef(PropertyDef $propertyDef);
    public function getPropertyDefSize();
    public function getPropertyDef($index);
    public function hasPropertyDef($propertyName);
}

interface InitMethodDefAware {
    public function addInitMethodDef(InitMethodDef $methodDef);
    public function getInitMethodDefSize();
    public function getInitMethodDef($index);
}

interface DestroyMethodDefAware {
    public function addDestroyMethodDef(DestroyMethodDef $methodDef);
    public function getDestroyMethodDefSize();
    public function getDestroyMethodDef($index);
}

interface AspectDefAware {
    public function addAspectDef(AspectDef $aspectDef);
    public function getAspectDefSize();
    public function getAspectDef($index);
}

interface ComponentDef
    extends
        ArgDefAware,
        PropertyDefAware,
        InitMethodDefAware,
        DestroyMethodDefAware,
        AspectDefAware,
        MetaDefAware {
    public function getComponent();
    public function injectDependency($outerComponent);
    public function getContainer();
    public function setContainer(S2Container $container);
    public function getComponentClass();
    public function getComponentName();
    public function getConcreteClass();
    public function getAutoBindingMode();
    public function setAutoBindingMode($mode);
    public function getInstanceMode();
    public function setInstanceMode($mode);
    public function getExpression();
    public function setExpression($expression);
    public function init();
    public function destroy();
}

class SimpleComponentDef implements ComponentDef {
    private $component_;
    private $componentClass_;
    private $componentName_;
    private $container_;
    public function SimpleComponentDef($component,$componentName="") {
        $this->component_ = $component;
        $this->componentClass_ = new ReflectionClass($component);
        $this->componentName_ = $componentName;
    }
    public function getComponent() {
        return $this->component_;
    }
    public function injectDependency($outerComponent) {
        throw new UnsupportedOperationException("injectDependency");
    }
    public function getContainer() {
        return $this->container_;
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
    }
    public function getComponentClass() {
        return $this->componentClass_;
    }
    public function getComponentName() {
        return $this->componentName_;
    }
    public function getConcreteClass() {
        return $this->componentClass_;
    }
    public function addArgDef(ArgDef $constructorArgDef) {
        throw new UnsupportedOperationException("addArgDef");
    }
    public function addPropertyDef(PropertyDef $propertyDef) {
        throw new UnsupportedOperationException("addPropertyDef");
    }
    public function addInitMethodDef(InitMethodDef $methodDef) {
        throw new UnsupportedOperationException("addInitMethodDef");
    }
    public function addDestroyMethodDef(DestroyMethodDef $methodDef) {
        throw new UnsupportedOperationException("addDestroyMethodDef");
    }
    public function addAspectDef(AspectDef $aspectDef) {
        throw new UnsupportedOperationException("addAspectDef");
    }
    public function getArgDefSize() {
        throw new UnsupportedOperationException("getArgDefSize");
    }
    public function getPropertyDefSize() {
        throw new UnsupportedOperationException("getPropertyDefSize");
    }
    public function getInitMethodDefSize() {
        throw new UnsupportedOperationException("getInitMethodDefSize");
    }
    public function getDestroyMethodDefSize() {
        throw new UnsupportedOperationException("getDestroyMethodDefSize");
    }
    public function getAspectDefSize() {
        throw new UnsupportedOperationException("getAspectDefSize");
    }
    public function getArgDef($index) {
        throw new UnsupportedOperationException("getArgDef");
    }
    public function getPropertyDef($index) {
        throw new UnsupportedOperationException("getPropertyDef");
    }
    public function hasPropertyDef($propertyName) {
        throw new UnsupportedOperationException("hasPropertyDef");
    }
    public function getInitMethodDef($index) {
        throw new UnsupportedOperationException("getInitMethodDef");
    }
    public function getDestroyMethodDef($index) {
        throw new UnsupportedOperationException("getDestroyMethodDef");
    }
    public function getAspectDef($index) {
        throw new UnsupportedOperationException("getAspectDef");
    }
    public function addMetaDef(MetaDef $metaDef) {
        throw new UnsupportedOperationException("addMetaDef");
    }
    public function getMetaDef($index) {
        throw new UnsupportedOperationException("getMetaDef");
    }
    public function getMetaDefs($name) {
        throw new UnsupportedOperationException("getMetaDefs");
    }
    public function getMetaDefSize() {
        throw new UnsupportedOperationException("getMetaDefSize");
    }
    public function getExpression() {
        throw new UnsupportedOperationException("getExpression");
    }
    public function setExpression($str) {
        throw new UnsupportedOperationException("setExpression");
    }
    public function getInstanceMode() {
        throw new UnsupportedOperationException("getInstanceMode");
    }
    public function setInstanceMode($instanceMode) {
        throw new UnsupportedOperationException("setInstanceMode");
    }
    public function getAutoBindingMode() {
        throw new UnsupportedOperationException("getAutoBindingMode");
    }
    public function setAutoBindingMode($autoBindingMode) {
        throw new UnsupportedOperationException("setAutoBindingMode");
    }
    public function init() {}
    public function destroy() {}
}

interface ContainerConstants {
    const INSTANCE_SINGLETON = "singleton";
    const INSTANCE_PROTOTYPE = "prototype";
    const INSTANCE_REQUEST = "request";
    const INSTANCE_SESSION = "session";
    const INSTANCE_OUTER = "outer";
    const AUTO_BINDING_AUTO = "auto";
    const AUTO_BINDING_CONSTRUCTOR = "constructor";
    const AUTO_BINDING_PROPERTY = "property";
    const AUTO_BINDING_NONE = "none";
    const NS_SEP = '.';
    const CONTAINER_NAME = "container";
    const REQUEST_NAME = "request";
    const RESPONSE_NAME = "response";
    const SESSION_NAME = "session";
    const SERVLET_CONTEXT_NAME = "servletContext";
    const COMPONENT_DEF_NAME = "componentDef";
}

class ComponentDefImpl implements ComponentDef {
    private $componentClass_;
    private $componentName_;
    private $concreteClass_;
    private $container_;
    private $expression_;
    private $argDefSupport_;
    private $propertyDefSupport_;
    private $initMethodDefSupport_;
    private $destroyMethodDefSupport_;
    private $aspectDefSupport_;
    private $metaDefSupport_;
    private $instanceMode_ = ContainerConstants::INSTANCE_SINGLETON;
    private $autoBindingMode_ = ContainerConstants::AUTO_BINDING_AUTO;
    private $componentDeployer_;
    public function ComponentDefImpl($componentClass="", $componentName="") {
        if($componentClass!=""){
        	$this->componentClass_ = new ReflectionClass($componentClass);
        }
        $this->componentName_ = $componentName;
        $this->argDefSupport_ = new ArgDefSupport();
        $this->propertyDefSupport_ = new PropertyDefSupport();
        $this->initMethodDefSupport_ = new InitMethodDefSupport();
        $this->destroyMethodDefSupport_ = new DestroyMethodDefSupport();
        $this->aspectDefSupport_ = new AspectDefSupport();
        $this->metaDefSupport_ = new MetaDefSupport();
    }
    public function getComponent() {
        return $this->getComponentDeployer()->deploy();
    }
    public function injectDependency($outerComponent) {
        $this->getComponentDeployer()->injectDependency($outerComponent);
    }
    public final function getComponentClass() {
        return $this->componentClass_;
    }
    public final function setComponentClass(ReflectionClass $componentClass) {
        $this->componentClass_ = $componentClass;
    }
    public final function getComponentName() {
        return $this->componentName_;
    }
    public final function getConcreteClass() {
        return $this->componentClass_;
    }
    public final function getContainer() {
        return $this->container_;
    }
    public final function setContainer(S2Container $container) {
        $this->container_ = $container;
        $this->argDefSupport_->setContainer($container);
        $this->metaDefSupport_->setContainer($container);
        $this->propertyDefSupport_->setContainer($container);
        $this->initMethodDefSupport_->setContainer($container);
        $this->destroyMethodDefSupport_->setContainer($container);
        $this->aspectDefSupport_->setContainer($container);
    }
    public function addArgDef(ArgDef $argDef) {
        $this->argDefSupport_->addArgDef($argDef);
    }
    public function addPropertyDef(PropertyDef $propertyDef) {
        $this->propertyDefSupport_->addPropertyDef($propertyDef);
    }
    public function addInitMethodDef(InitMethodDef $methodDef) {
        $this->initMethodDefSupport_->addInitMethodDef($methodDef);
    }
    public function addDestroyMethodDef(DestroyMethodDef $methodDef) {
        $this->destroyMethodDefSupport_->addDestroyMethodDef($methodDef);
    }
    public function addAspectDef(AspectDef $aspectDef) {
        $this->aspectDefSupport_->addAspectDef($aspectDef);
        $this->concreteClass_ = null;
    }
    public function getArgDefSize() {
        return $this->argDefSupport_->getArgDefSize();
    }
    public function getPropertyDefSize() {
        return $this->propertyDefSupport_->getPropertyDefSize();
    }
    public function getInitMethodDefSize() {
        return $this->initMethodDefSupport_->getInitMethodDefSize();
    }
    public function getDestroyMethodDefSize() {
        return $this->destroyMethodDefSupport_->getDestroyMethodDefSize();
    }
    public function getAspectDefSize() {
        return $this->aspectDefSupport_->getAspectDefSize();
    }
    public function getInstanceMode() {
        return $this->instanceMode_;
    }
    public function setInstanceMode($instanceMode) {
        if (InstanceModeUtil::isSingleton($instanceMode)
                || InstanceModeUtil::isPrototype($instanceMode)
                || InstanceModeUtil::isRequest($instanceMode)
                || InstanceModeUtil::isSession($instanceMode)
                || InstanceModeUtil::isOuter($instanceMode)) {
            $this->instanceMode_ = $instanceMode;
        } else {
            throw new IllegalArgumentException($instanceMode);
        }
    }
    public function getAutoBindingMode() {
        return $this->autoBindingMode_;
    }
    public function setAutoBindingMode($autoBindingMode) {
        if (AutoBindingUtil::isAuto($autoBindingMode)
                || AutoBindingUtil::isConstructor($autoBindingMode)
                || AutoBindingUtil::isProperty($autoBindingMode)
                || AutoBindingUtil::isNone($autoBindingMode)) {
            $this->autoBindingMode_ = $autoBindingMode;
        } else {
            throw new IllegalArgumentException(autoBindingMode);
        }
    }
    public function init() {
        $this->getComponentDeployer()->init();
    }
    public function destroy() {
        $this->getComponentDeployer()->destroy();
    }
    public function getExpression() {
        return $this->expression_;
    }
    public function setExpression($expression) {
        $this->expression_ = $expression;
    }
    public function getArgDef($index) {
        return $this->argDefSupport_->getArgDef($index);
    }
    public function getPropertyDef($index) {
        return $this->propertyDefSupport_->getPropertyDef($index);
    }
    public function hasPropertyDef($propertyName) {
        return $this->propertyDefSupport_->hasPropertyDef($propertyName);
    }
    public function getInitMethodDef($index) {
        return $this->initMethodDefSupport_->getInitMethodDef($index);
    }
    public function getDestroyMethodDef($index) {
        return $this->destroyMethodDefSupport_->getDestroyMethodDef($index);
    }
    public function getAspectDef($index) {
        return $this->aspectDefSupport_->getAspectDef($index);
    }
    public function addMetaDef(MetaDef $metaDef) {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    public function getMetaDef($name) {
        return $this->metaDefSupport_->getMetaDef($name);
    }
    public function getMetaDefs($name) {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    public function getMetaDefSize() {
        return $this->metaDefSupport_->getMetaDefSize();
    }
    private function getComponentDeployer() {
        if ($this->componentDeployer_ == null) {
            $this->componentDeployer_ = ComponentDeployerFactory::create($this);
        }
        return $this->componentDeployer_;
    }   
}

final class ArgDefSupport {
    private $argDefs_ = array();
    private $container_;
    public function ArgDefSupport() {
    }
    public function addArgDef(ArgDef $argDef) {
        if ($this->container_ != null) {
            $argDef->setContainer($this->container_);
        }
        array_push($this->argDefs_,$argDef);
    }
    public function getArgDefSize() {
        return count($this->argDefs_);
    }
    public function getArgDef($index) {
        return $this->argDefs_[$index];
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for($i=0;$i<$this->getArgDefSize();$i++) {
            $this->getArgDef($i)->setContainer($container);
        }
    }
}

final class PropertyDefSupport {
    private $propertyDefs_ = array();
    private $propertyDefList_ = array();
    private $container_;
    public function PropertyDefSupport() {
    }
    public function addPropertyDef(PropertyDef $propertyDef) {
        if ($this->container_ != null) {
            $propertyDef->setContainer($this->container_);
        }
        $this->propertyDefs_[$propertyDef->getPropertyName()] = $propertyDef;
        array_push($this->propertyDefList_,$propertyDef->getPropertyName());
    }
    public function getPropertyDefSize() {
        return count($this->propertyDefs_);
    }
    public function getPropertyDef($propertyName) {
        if(is_int($propertyName)){
            return $this->propertyDefs_[$this->propertyDefList_[$propertyName]];
        }
        return $this->propertyDefs_[$propertyName];
    }
    public function hasPropertyDef($propertyName) {
        return array_key_exists($propertyName,$this->propertyDefs_);
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getPropertyDefSize(); ++$i) {
            $this->getPropertyDef($i)->setContainer($container);
        }
    }
}

final class InitMethodDefSupport {
    private $methodDefs_ = array();
    private $container_;
    public function InitMethodDefSupport() {
    }
    public function addInitMethodDef(InitMethodDef $methodDef) {
        if ($this->container_ != null) {
            $methodDef->setContainer($this->container_);
        }
        array_push($this->methodDefs_,$methodDef);
    }
    public function getInitMethodDefSize() {
        return count($this->methodDefs_);
    }
    public function getInitMethodDef($index) {
        return $this->methodDefs_[$index];
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0;$i < $this->getInitMethodDefSize(); ++$i) {
            $this->getInitMethodDef($i)->setContainer($container);
        }
    }
}

final class DestroyMethodDefSupport {
    private $methodDefs_ = array();
    private $container_;
    public function DestroyMethodDefSupport() {
    }
    public function addDestroyMethodDef(DestroyMethodDef $methodDef) {
        if ($this->container_ != null) {
            $methodDef->setContainer($this->container_);
        }
        array_push($this->methodDefs_,$methodDef);
    }
    public function getDestroyMethodDefSize() {
        return count($this->methodDefs_);
    }
    public function getDestroyMethodDef($index) {
        return $this->methodDefs_[$index];
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getDestroyMethodDefSize(); ++$i) {
            $this->getDestroyMethodDef($i)->setContainer($container);
        }
    }
}

final class AspectDefSupport {
    private $aspectDefs_ = array();
    private $container_;
    public function AspectDefSupport() {
    }
    public function addAspectDef(AspectDef $aspectDef) {
        if ($this->container_ != null) {
            $aspectDef->setContainer($this->container_);
        }
        array_push($this->aspectDefs_,$aspectDef);
    }
    public function getAspectDefSize() {
        return count($this->aspectDefs_);
    }
    public function getAspectDef($index) {
        return $this->aspectDefs_[$index];
    }
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getAspectDefSize(); ++$i) {
            $this->getAspectDef($i)->setContainer($container);
        }
    }
}

interface ArgDef extends MetaDefAware {
    public function getValue();
    public function getContainer();
    public function setContainer($container);
    public function getExpression();
    public function setExpression($str);
    public function setChildComponentDef(ComponentDef $componentDef);
}

class ArgDefImpl implements ArgDef {
    private $value_;
    private $container_;
    private $expression_ = "";
    private $exp_ = null;
    private $childComponentDef_ = null;
    private $metaDefSupport_;
    public function ArgDefImpl($value=null) {
        $this->metaDefSupport_ = new MetaDefSupport();
        if($value != null){
            $this->setValue($value);
        }
    }
    public final function getValue() {
        if ($this->exp_ != null) {
            return eval($this->exp_);
        }
        if ($this->childComponentDef_ != null) {
            return $this->childComponentDef_->getComponent();
        }
        return $this->value_;
    }
    public final function setValue($value) {
        $this->value_ = $value;
    }
    public final function getContainer() {
        return $this->container_;
    }
    public final function setContainer($container) {
        $this->container_ = $container;
        if ($this->childComponentDef_ != null) {
            $this->childComponentDef_->setContainer($container);
        }
        $this->metaDefSupport_->setContainer($container);
    }
    public final function getExpression() {
        return $this->expression_;
    }
    public final function setExpression($expression) {
        $this->expression_ = trim($expression);
        if($this->expression_ == ""){
            $this->exp_ = null;
        }else{
            $this->exp_ = EvalUtil::getExpression($this->expression_);
        }
    }
    public final function setChildComponentDef(ComponentDef $componentDef) {
        if ($this->container_ != null) {
            $componentDef->setContainer($this->container_);
        }
        $this->childComponentDef_ = $componentDef;
    }
    public function addMetaDef(MetaDef $metaDef) {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }
    public function getMetaDef($indexOrName) {
        return $this->metaDefSupport_->getMetaDef($indexOrName);
    }
    public function getMetaDefs($name) {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    public function getMetaDefSize() {
        return $this->metaDefSupport_->getMetaDefSize();
    }
}

interface PropertyDef extends ArgDef {
    public function getPropertyName();
}

class PropertyDefImpl extends ArgDefImpl implements PropertyDef {
    private $propertyName_;
    public function PropertyDefImpl($propertyName=null, $value=null) {
        parent::__construct($value);
        if($propertyName != null){
            $this->propertyName_ = $propertyName;
        }
    }
    public function getPropertyName() {
        return $this->propertyName_;
    }
}

final class EvalUtil {
    private function EvalUtil() {
    }
    public static function getExpression($expression){
        $exp = $expression;
        if(!preg_match("/\sreturn\s/",$exp) and 
           !preg_match("/\nreturn\s/",$exp) and
           !preg_match("/^return\s/",$exp)){
            $exp = "return " . $exp;
        }
        if(!preg_match("/;$/",$exp)){
            $exp = $exp . ";";
        }
        return $exp;
    } 
    public static function addSemiColon($expression){
        $exp = trim($expression);
        if(!preg_match("/;$/",$exp)){
            $exp = $exp . ";";
        }
        return $exp;
    } 
}

interface Pointcut { 
    public function isApplied($methodName);
}

final class PointcutImpl implements Pointcut {
    private $methodNames_;
    private $patterns_;
    public function PointcutImpl($target=null) {
        if(!is_array($target)){
            if ($target == null) {
                throw new EmptyRuntimeException("targetClass");
            }
            if($target instanceof ReflectionClass){
                $this->setMethodNames($this->getMethodNames($target));
            }else{
                $this->setMethodNames($this->getMethodNames(
                                       new ReflectionClass($target)));
            }
        }else{
            if (count($target) == 0) {
                throw new EmptyRuntimeException("methodNames");
            }
            $this->setMethodNames($target);
        }
    }
    public function isApplied($methodName) {
        for ($i = 0;$i < count($this->methodNames_); ++$i) {
        	if(preg_match("/^!(.+)/",$this->methodNames_[$i],$regs)){
                if(!preg_match("/".$regs[1]."/",$methodName)){
               	    return true;
                }
        	}else{
                if(preg_match("/".$this->methodNames_[$i]."/",$methodName)){
               	    return true;
                }
        	}
        }
        return false;
    }
    private function setMethodNames($methodNames) {
        $this->methodNames_ = $methodNames;
    }
    private function getMethodNames($targetClass=null) {
        if($targetClass == null){
            return $this->methodNames_;
        }
        $methodNameSet = array();
        if($targetClass->isInterface() or $targetClass->isAbstract()){
            $methods = $targetClass->getMethods();
            for ($j = 0; $j < count($methods); $j++) {
                array_push($methodNameSet,$methods[$j]->getName());
            }
        }else{
            $interfaces = $targetClass->getInterfaces();
            for ($i = 0; $i < count($interfaces); $i++) {
                $methods = $interfaces[$i]->getMethods();
                for ($j = 0; $j < count($methods); $j++) {
                    array_push($methodNameSet,$methods[$j]->getName());
                }
            }
        }
        return $methodNameSet;
    }
}

interface AspectDef extends ArgDef {
    public function getAspect();
}

class AspectDefImpl extends ArgDefImpl implements AspectDef {
    private $pointcut_;
    public function AspectDefImpl($arg1=null,$arg2=null) {
        parent::__construct();
        if($arg1 instanceof Pointcut){
            $this->pointcut_ = $arg1;
        }
        if($arg2 instanceof Pointcut){
            $this->pointcut_ = $arg2;
        }
        if($arg1 instanceof MethodInterceptor){
            $this->setValue($arg1);
        }
        if($arg2 instanceof MethodInterceptor){
            $this->setValue($arg2);
        }
    }
    public function getAspect() {
        $interceptor = $this->getValue();
        return new AspectImpl($interceptor, $this->pointcut_);
    }
}final class ComponentDeployerFactory {
    private function ComponentDeployerFactory() {
    }
    public static function create(ComponentDef $componentDef) {
        if (InstanceModeUtil::isSingleton($componentDef->getInstanceMode())) {
            return new SingletonComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isPrototype($componentDef->getInstanceMode())) {
            return new PrototypeComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isRequest($componentDef->getInstanceMode())) {
            return new RequestComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isSession($componentDef->getInstanceMode())) {
            return new SessionComponentDeployer($componentDef);
        } else {
            return new OuterComponentDeployer($componentDef);
        }
    }
}

final class InstanceModeUtil {
    private function InstanceModeUtil() {
    }
    public static final function isSingleton($mode) {
        return strtolower(ContainerConstants::INSTANCE_SINGLETON)
                == strtolower($mode);
    }
    public static final function isPrototype($mode) {
        return strtolower(ContainerConstants::INSTANCE_PROTOTYPE)
                == strtolower($mode);
    }
    public static final function isRequest($mode) {
        return strtolower(ContainerConstants::INSTANCE_REQUEST)
                == strtolower($mode);
    }
    public static final function isSession($mode) {
        return strtolower(ContainerConstants::INSTANCE_SESSION)
                == strtolower($mode);
    }
    public static final function isOuter($mode) {
        return strtolower(ContainerConstants::INSTANCE_OUTER)
                == strtolower($mode);
    }
}

interface ComponentDeployer {
    public function deploy();
    public function injectDependency($outerComponent);
    public function init();
    public function destroy();
}

abstract class AbstractComponentDeployer implements ComponentDeployer {
    private $componentDef_;
    private $constructorAssembler_;
    private $propertyAssembler_;
    private $initMethodAssembler_;
    private $destroyMethodAssembler_;
    public function AbstractComponentDeployer(ComponentDef $componentDef) {
        $this->componentDef_ = $componentDef;
        $this->setupAssembler();
    }
    protected final function getComponentDef() {
        return $this->componentDef_;
    }
    protected final function getConstructorAssembler() {
        return $this->constructorAssembler_;
    }
    protected final function getPropertyAssembler() {
        return $this->propertyAssembler_;
    }
    protected final function getInitMethodAssembler() {
        return $this->initMethodAssembler_;
    }
    protected final function getDestroyMethodAssembler() {
        return $this->destroyMethodAssembler_;
    }
    private function setupAssembler() {
        $autoBindingMode = $this->componentDef_->getAutoBindingMode();
        if (AutoBindingUtil::isAuto($autoBindingMode)) {
            $this->setupAssemblerForAuto();
        } else if (AutoBindingUtil::isConstructor($autoBindingMode)) {
            $this->setupAssemblerForConstructor();
        } else if (AutoBindingUtil::isProperty($autoBindingMode)) {
            $this->setupAssemblerForProperty();
        } else if (AutoBindingUtil::isNone($autoBindingMode)) {
            $this->setupAssemblerForNone();
        } else {
            throw new IllegalArgumentException($autoBindingMode);
        }
        $this->initMethodAssembler_ = new DefaultInitMethodAssembler($this->componentDef_);
        $this->destroyMethodAssembler_ = new DefaultDestroyMethodAssembler($this->componentDef_);
    }
    private function setupAssemblerForAuto() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new AutoPropertyAssembler($this->componentDef_);
    }
    private function setupConstructorAssemblerForAuto() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new AutoConstructorAssembler($this->componentDef_);
        }
    }
    private function setupAssemblerForConstructor() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new ManualPropertyAssembler($this->componentDef_);
    }
    private function setupAssemblerForProperty() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ = new ManualConstructorAssembler($this->componentDef_);
        }
        $this->propertyAssembler_ = new AutoPropertyAssembler($this->componentDef_);
    }
    private function setupAssemblerForNone() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new DefaultConstructorAssembler($this->componentDef_);
        }
        if ($this->componentDef_->getPropertyDefSize() > 0) {
            $this->propertyAssembler_ = new ManualPropertyAssembler($this->componentDef_);
        } else {
            $this->propertyAssembler_ = new DefaultPropertyAssembler($this->componentDef_);
        }
    }
}

class SingletonComponentDeployer extends AbstractComponentDeployer {
    private $component_;
    private $instantiating_ = false;
    public function SingletonComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function deploy() {
        if ($this->component_ == null) {
            $this->assemble();
        }
        return $this->component_;
    }
    public function injectDependency($component) {
        throw new UnsupportedOperationException("injectDependency");
    }
    private function assemble() {
        if ($this->instantiating_) {
            throw new CyclicReferenceRuntimeException(
                $this->getComponentDef()->getComponentClass());
        }
        $this->instantiating_ = true;
        $this->component_ = $this->getConstructorAssembler()->assemble();
        $this->instantiating_ = false;
        $this->getPropertyAssembler()->assemble($this->component_);
        $this->getInitMethodAssembler()->assemble($this->component_);
    }
    public function init() {
        $this->deploy();
    }
    public function destroy() {
        if ($this->component_ == null) {
            return;
        }
        $this->getDestroyMethodAssembler()->assemble($this->component_);
        $this->component_ = null;
    }
}

final class AutoBindingUtil {
    private function AutoBindingUtil() {
    }
    public static final function isSuitable($classes) {
        if(is_array($classes)){
            for ($i = 0; $i < count($classes); ++$i) {
                if (!AutoBindingUtil::isSuitable($classes[$i])) {
                    return false;
                }
            }
        }else{
            if($classes != null){
                return $classes->isInterface();
            }else{
                return false;
            }
        }
        return true;
    }
    public static final function isAuto($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_AUTO)
                == strtolower($mode);
    }
    public static final function isConstructor($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_CONSTRUCTOR)
                == strtolower($mode);
    }
    public static final function isProperty($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_PROPERTY)
                == strtolower($mode);
    }
    public static final function isNone($mode) {
        return strtolower(ContainerConstants::AUTO_BINDING_NONE)
                == strtolower($mode);
    }
}

abstract class AbstractAssembler {
    private $log_;
    private $componentDef_;
    public function AbstractAssembler(ComponentDef $componentDef) {
        $this->componentDef_ = $componentDef;
        $this->log_ = S2Logger::getLogger(get_class($this));
    }
    protected final function getComponentDef() {
        return $this->componentDef_;
    }
    protected final function getBeanDesc($component=null) {
        if(!is_object($component)){
            return BeanDescFactory::getBeanDesc(
                $this->getComponentDef()->getComponentClass());
        }
        return BeanDescFactory::getBeanDesc(
            $this->getComponentClass($component));
    }
    protected final function getComponentClass($component) {
        $clazz = $this->componentDef_->getComponentClass();
        if ($clazz != null) {
            return $clazz;
        } else {
            return new ReflectionClass($component);
        }
    }
    protected function getArgs($argTypes) {
        $args = array();
        for ($i = 0; $i < count($argTypes); ++$i) {
            try {
                if($argTypes[$i]->getClass() != null &&
                    AutoBindingUtil::isSuitable($argTypes[$i]->getClass())){
                    $args[$i]= $this->getComponentDef()->getContainer()->getComponent($argTypes[$i]->getClass()->getName());
                }else{
                	if($argTypes[$i]->isOptional()){
                        $args[$i] = $argTypes[$i]->getDefaultValue();
                	}else{
                		$args[$i] = null;
                	}
                }
            } catch (ComponentNotFoundRuntimeException $ex) {
                $this->log_->warn($ex->getMessage(),__METHOD__);
                $args[$i] = null;
            }
        }
        return $args;
    }
}

interface ConstructorAssembler {
    public function assemble();
}

abstract class AbstractConstructorAssembler extends AbstractAssembler
        implements ConstructorAssembler {
    public function AbstractConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function assembleDefault() {
        $clazz = $this->getComponentDef()->getConcreteClass();
        return ConstructorUtil::newInstance($clazz, null,$this->getComponentDef());
    }
}

final class ManualConstructorAssembler
    extends AbstractConstructorAssembler {
    public function ManualConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble(){
        $args = array();
        for ($i = 0; $i < $this->getComponentDef()->getArgDefSize(); ++$i) {
            try {
                $args[$i] = $this->getComponentDef()->getArgDef($i)->getValue();
            } catch (ComponentNotFoundRuntimeException $cause) {
                throw new IllegalConstructorRuntimeException(
                    $this->getComponentDef()->getComponentClass(),
                    $cause);
            }
        }
        $beanDesc =
            BeanDescFactory::getBeanDesc($this->getComponentDef()->getConcreteClass());
        return $beanDesc->newInstance($args,$this->getComponentDef());
    }
}

final class S2Logger {
    private static $loggerMap_ = array();
    private $log_;
    private function S2Logger($className) {
        $this->log_ = S2LogFactory::getLog($className);
    }
    public static final function getLogger($className) {
        $logger = null;
        if(array_key_exists($className,S2Logger::$loggerMap_)){
            $logger = S2Logger::$loggerMap_[$className];
        }
        if ($logger == null) {
            $logger = new S2Logger($className);
            S2Logger::$loggerMap_[$className] = $logger;
        }
        return $logger->getLog();
    }
    private function getLog(){
        return $this->log_;
    }
}

class S2LogFactory {
    private function LogFactory() {
    }
    public static function getLog($className){
        return new SimpleLogger($className);
    }
}

class SimpleLogger {
    private $className;
    const DEBUG = 1;
    const INFO  = 2;
    const WARN  = 3;
    const ERROR = 4;
    const FATAL = 5;
    function SimpleLogger($className) {
        $this->className = $className;
        if(!defined('S2CONTAINER_PHP5_LOG_LEVEL')){
            define('S2CONTAINER_PHP5_LOG_LEVEL',3);
        }
    }
    private function cli($level,$msg="",$methodName=""){
        switch($level){
            case SimpleLogger::DEBUG :
                $logLevel = "DEBUG";
                break;    
            case SimpleLogger::INFO :
                $logLevel = "INFO";
                break;    
            case SimpleLogger::WARN :
                $logLevel = "WARN";
                break;    
            case SimpleLogger::ERROR :
                $logLevel = "ERROR";
                break;    
            case SimpleLogger::FATAL :
                $logLevel = "FATAL";
                break;    
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL <= $level){
            printf("[%-5s] %s - %s\n",$logLevel,$methodName,$msg);
        }
    }
    public function debug($msg="",$methodName=""){
        $this->cli(SimpleLogger::DEBUG,$msg,$methodName);
    }
    public function info($msg="",$methodName=""){
        $this->cli(SimpleLogger::INFO,$msg,$methodName);
    }
    public function warn($msg="",$methodName=""){
        $this->cli(SimpleLogger::WARN,$msg,$methodName);
    }
    public function error($msg="",$methodName=""){
        $this->cli(SimpleLogger::ERROR,$msg,$methodName);
    }
    public function fatal($msg="",$methodName=""){
        $this->cli(SimpleLogger::FATAL,$msg,$methodName);
    }
}

interface PropertyAssembler {
    public function assemble($component);
}

abstract class AbstractPropertyAssembler
    extends AbstractAssembler
    implements PropertyAssembler {
    public function AbstractPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function getValue(PropertyDef $propertyDef, $component) {
        try {
            return $propertyDef->getValue();
        } catch (ComponentNotFoundRuntimeException $cause) {
            throw new IllegalPropertyRuntimeException(
                $this->getComponentClass($component),
                $propertyDef->getPropertyName(),
                $cause);
        }
    }
    protected function setValue(
        PropertyDesc $propertyDesc,
        $component,
        $value){
        if ($value == null) {
            return;
        }
        try {
            $propertyDesc->setValue($component,$value);
        } catch (Exception $ex) {
            throw new IllegalPropertyRuntimeException(
                $this->getComponentDef()->getComponentClass(),
                $propertyDesc->getPropertyName(),
                $ex);
        }
    }
}

class ManualPropertyAssembler extends AbstractPropertyAssembler {
    public function ManualPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble($component) {
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getPropertyDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $propDef = $this->getComponentDef()->getPropertyDef($i);
            $value = $this->getValue($propDef, $component);
            try{
                $propDesc =
                    $beanDesc->getPropertyDesc($propDef->getPropertyName());
            }catch(PropertyNotFoundRuntimeException $e1){
                try{
                	$propDesc =
                        $beanDesc->getPropertyDesc('__set');
                    $propDesc->setSetterPropertyName($propDef->getPropertyName());    
                }catch(PropertyNotFoundRuntimeException $e2){
                    throw $e1;
                }
            }
            if(!$propDesc->hasWriteMethod()){
            	$propDesc =
                    $beanDesc->getPropertyDesc('__set');
                $propDesc->setSetterPropertyName($propDef->getPropertyName());    
            }    
            $this->setValue($propDesc,$component,$value);
        }
    }
}

class AutoPropertyAssembler extends ManualPropertyAssembler {
    private $log_;
    public function AutoPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
        $this->log_ = S2Logger::getLogger(get_class($this));
    }
    public function assemble($component) {
        parent::assemble($component);
        $beanDesc = $this->getBeanDesc($component);
        $container = $this->getComponentDef()->getContainer();
        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $value = null;
            $propDesc = $beanDesc->getPropertyDesc($i);
            $propName = $propDesc->getPropertyName();
            if (!$this->getComponentDef()->hasPropertyDef($propName) and
                $propDesc->getWriteMethod() != null and
                AutoBindingUtil::isSuitable($propDesc->getPropertyType())) {
                try {
                    $value = $container->getComponent($propDesc->getPropertyType()->getName());
                } catch (ComponentNotFoundRuntimeException $ex) {
                    if ($propDesc->getReadMethod() != null and
                        $propDesc->getValue($component) != null) {
                        continue;
                    }
                    $this->log_->info($ex->getMessage().". skip property<$propName>.",
                                      __METHOD__);
                    continue;
                }
                $this->setValue($propDesc,$component,$value);
            }
        }
    }
}

interface MethodAssembler {
    public function assemble($component);
}

abstract class AbstractMethodAssembler
    extends AbstractAssembler
    implements MethodAssembler {
    public function AbstractMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function invoke(
        BeanDesc $beanDesc,
        $component,
        MethodDef $methodDef) {
        $expression = $methodDef->getExpression();
        $methodName = $methodDef->getMethodName();
        if ($methodName != null) {
            $args = array();
            $method = null;
            try {
                if ($methodDef->getArgDefSize() > 0) {
                    $args = $methodDef->getArgs();
                } else {
                    $methods = $beanDesc->getMethods($methodName);
                    $method = $this->getSuitableMethod($methods);
                    if ($method != null) {
                        $args = $this->getArgs($method->getParameters());
                    }
                }
            } catch (ComponentNotFoundRuntimeException $cause) {
                throw new IllegalMethodRuntimeException(
                    $this->getComponentClass($component),
                    $methodName,
                    $cause);
            }
            if ($method != null) {
                MethodUtil::invoke($method, $component, $args);
            } else {
                $this->invoke0($beanDesc,$component,$methodName,$args);
            }
        } else {
            $this->invokeExpression($component,$expression);
        }
    }
    private function invokeExpression($component,$expression) {
    	$exp = EvalUtil::addSemiColon($expression);
    	eval($exp);
    }
    private function getSuitableMethod($methods) {
        $params = $methods->getParameters();
        $suitable = 1;
        if(count($params) == 0){
            return $methods;
        }
        foreach($params as $param){
            if($param->getClass() != null){
                if(!AutoBindingUtil::isSuitable($param->getClass())){
                    $suitable *= 0;
                }                    
            }
        }
        if($suitable == 1){
            return $methods;
        }
        return null;
    }
    private function invoke0(
        BeanDesc $beanDesc,
        $component,
        $methodName,
        $args){
        try {
            $beanDesc->invoke($component,$methodName,$args);
        } catch (Exception $ex) {
            throw new IllegalMethodRuntimeException(
                $this->getComponentClass($component),
                $methodName,
                $ex);
        }
    }
}

class DefaultInitMethodAssembler extends AbstractMethodAssembler {
    public function DefaultInitMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble($component){
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getInitMethodDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $methodDef = $this->getComponentDef()->getInitMethodDef($i);
            $this->invoke($beanDesc,$component,$methodDef);
        }
    }
}

class DefaultDestroyMethodAssembler extends AbstractMethodAssembler {
    public function DefaultDestroyMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble($component){
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getDestroyMethodDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $methodDef = $this->getComponentDef()->getDestroyMethodDef($i);
            $this->invoke($beanDesc,$component,$methodDef);
        }
    }
}

class S2RuntimeException extends Exception {
    private $messageCode_;
    private $args_;
    private $message_;
    private $simpleMessage_;
    public function S2RuntimeException(
        $messageCode,
        $args = null,
        $cause = null) {
        $cause instanceof Exception ? 
            $msg = $cause->getMessage() . "\n" :
            $msg = "";
        $msg .= MessageUtil::getMessageWithArgs($messageCode,$args);
        parent::__construct($msg);
    }
}

class ComponentNotFoundRuntimeException extends S2RuntimeException {
    private $componentKey_;
    public function ComponentNotFoundRuntimeException($componentKey) {
        parent::__construct("ESSR0046", array($componentKey));
        $this->componentKey_ = $componentKey;
    }
    public function getComponentKey() {
        return $this->componentKey_;
    }
}

final class BeanDescFactory {
    private static $beanDescCache_ = array();
    private function BeanDescFactory() {
    }
    public static function getBeanDesc(ReflectionClass $clazz) {
    	if(array_key_exists($clazz->getName(),BeanDescFactory::$beanDescCache_)){
            $beanDesc = BeanDescFactory::$beanDescCache_[$clazz->getName()];
    	}else{
            $beanDesc = new BeanDescImpl($clazz);
            BeanDescFactory::$beanDescCache_[$clazz->getName()] = $beanDesc;
        }
        return $beanDesc;
    }
}

interface BeanDesc {
    public function getBeanClass();
    public function hasPropertyDesc($propertyName);
    public function getPropertyDesc($propertyName);
    public function getPropertyDescSize();
    public function hasField($fieldName);
    public function getField($fieldName);
    public function newInstance($args);
    public function getSuitableConstructor();
    public function invoke($target, $methodName,$args);
    public function getMethods($methodName);
    public function hasMethod($methodName);
    public function getMethodNames();
    public function hasConstant($constName);
    public function getConstant($constName);
}

final class BeanDescImpl implements BeanDesc {
    private static $EMPTY_ARGS = array();
    private $beanClass_;
    private $constructors_;
    private $propertyDescCache_ = array();
    private $propertyDescCacheIndex_ = array();
    private $methodsCache_ = array();
    private $fieldCache_ = array();
    private $constCache_ = array();
    public function BeanDescImpl(ReflectionClass $beanClass) {
        $this->beanClass_ = $beanClass;
        $this->constructors_ = $this->beanClass_->getConstructor();
        $this->setupMethods();
        $this->setupPropertyDescs();
        $this->setupField();
        $this->setupConstant();
    }
    public function getBeanClass() {
        return $this->beanClass_;
    }
    public function hasPropertyDesc($propertyName) {
        return array_key_exists($propertyName,$this->propertyDescCache_);
    }
    public function getPropertyDesc($propertyName) {
        if(is_int($propertyName)){
            if ($propertyName >= count($this->propertyDescCacheIndex_)) {
                throw new PropertyNotFoundRuntimeException(
                    $this->beanClass_, 'index '.$propertyName);
            }
            return $this->propertyDescCache_[
                       $this->propertyDescCacheIndex_[$propertyName]];
        }
        $pd = null;
        if(array_key_exists($propertyName,$this->propertyDescCache_)){
            $pd = $this->propertyDescCache_[$propertyName];
        }
        if ($pd == null) {
            throw new PropertyNotFoundRuntimeException(
                $this->beanClass_, $propertyName);
        }
        return $pd;
    }
    private function getPropertyDesc0($propertyName) {
        if(array_key_exists($propertyName,$this->propertyDescCache_)){
            return $this->propertyDescCache_[$propertyName];
        }else{
            return null;
        }
    }
    public function getPropertyDescSize() {
        return count($this->propertyDescCache_);
    }
    public function hasField($fieldName) {
        return array_key_exists($fieldName,$this->fieldCache_);
    }
    public function getField($fieldName) {
        if(array_key_exists($fieldName,$this->fieldCache_)){
            $field = $this->fieldCache_[$fieldName];
        }else{
            throw new FieldNotFoundRuntimeException($this->beanClass_, $fieldName);
        }
        return $field;
    }
    public function hasConstant($constName) {
        return array_key_exists($constName,$this->constCache_);
    }
    public function getConstant($constName) {
        if(array_key_exists($constName,$this->constCache_)){
            $constant = $this->constCache_[$constName];
        }else{
            throw new ConstantNotFoundRuntimeException($this->beanClass_, $constName);
        }
        return $constant;
    }
    public function newInstance($args,$componentDef=null){
        return ConstructorUtil::newInstance($this->beanClass_, $args,$componentDef);
    }
    public function invoke($target,$methodName,$args) {
        $method = $this->getMethods($methodName);
        return MethodUtil::invoke($method,$target,$args);
    }
    public function getSuitableConstructor() {
    	return $this->constructors_;
    }
    public function getMethods($methodName){
        if(array_key_exists($methodName,$this->methodsCache_)){
            $methods = $this->methodsCache_[$methodName];
        }else{
            throw new MethodNotFoundRuntimeException($this->beanClass_, $methodName, null);
        }
        return $methods;
    }
    public function hasMethod($methodName) {
        if(array_key_exists($methodName,$this->methodsCache_)){
            return $this->methodsCache_[$methodName] != null;
        }else{
            return false;
        }
    }
    public function getMethodNames() {
        return array_keys($this->methodsCache_);
    }
    private function isFirstCapitalize($str){
        $top = substr($str,0,1);
        $upperTop = strtoupper($top);
        return $upperTop == $top;
    }
    private function setupPropertyDescs() {
        $methods = $this->beanClass_->getMethods();
        for ($i = 0; $i < count($methods); $i++) {
            $mRef = $methods[$i];
            $methodName = $mRef->getName();
            if (preg_match("/^get(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 0){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName = 
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupReadMethod($mRef, $propertyName);
            } else if (preg_match("/^is(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 0){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName =
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupReadMethod($mRef, $propertyName);
            } else if (preg_match("/^set(.+)/",$methodName,$regs)) {
                if (count($mRef->getParameters()) != 1){
                    continue;
                }
                if(!$this->isFirstCapitalize($regs[1])){
                    continue;
                }
                $propertyName =
                    $this->decapitalizePropertyName($regs[1]);
                $this->setupWriteMethod($mRef, $propertyName);
            } else if (preg_match("/^(__set)$/",$methodName,$regs)) {
                $propertyName = $regs[1];
                $this->setupWriteMethod($mRef, $propertyName);
            }
        }
    }
    private function decapitalizePropertyName($name) {
        $top = substr($name,0,1);
        $top = strtolower($top);
        return substr_replace($name,$top,0,1);
    }
    private function addPropertyDesc(PropertyDesc $propertyDesc) {
        $this->propertyDescCache_[$propertyDesc->getPropertyName()] = $propertyDesc;
        array_push($this->propertyDescCacheIndex_,$propertyDesc->getPropertyName());
    }
    private function setupReadMethod($readMethod, $propertyName) {
		$propDesc = $this->getPropertyDesc0($propertyName);
		if ($propDesc != null) {
			$propDesc->setReadMethod($readMethod);
		} else {
    		$writeMethod = null;	
			$propDesc =
				new PropertyDescImpl(
					$propertyName,
					null,
					$readMethod,
					null,
					$this);
			$this->addPropertyDesc($propDesc);
		}
    }
    private function setupWriteMethod($writeMethod,$propertyName) {
        $propDesc = $this->getPropertyDesc0($propertyName);
        if ($propDesc != null) {
            $propDesc->setWriteMethod($writeMethod);
        } else {
            if($propertyName == "__set"){
                $propDesc =
                    new UuSetPropertyDescImpl(
                        $propertyName,
                        null,
                        null,
                        $writeMethod,
                        $this);
            }else{
                $propertyTypes = $writeMethod->getParameters();
                $propDesc =
                    new PropertyDescImpl(
                        $propertyName,
                        $propertyTypes[0]->getClass(),
                        null,
                        $writeMethod,
                        $this);
            }
            $this->addPropertyDesc($propDesc);
        }       
    }
    private function setupMethods() {
        $methods = $this->beanClass_->getMethods();
        for ($i = 0; $i < count($methods); $i++) {
            $this->methodsCache_[$methods[$i]->getName()] = $methods[$i];
        }
    }
    private function setupField() {
        $fields = $this->beanClass_->getProperties();
        for ($i = 0; $i < count($fields); $i++) {
            if ($fields[$i]->isStatic()) {
                $this->fieldCache_[$fields[$i]->getName()] = $fields[$i];
            }
        }
    }
    private function setupConstant() {
        $this->constCache_ = $this->beanClass_->getConstants();
    }
}

interface PropertyDesc {
    public function getPropertyName();
    public function getPropertyType();
    public function getReadMethod();
    public function setReadMethod($readMethod);
    public function hasReadMethod();
    public function getWriteMethod();
    public function setWriteMethod($writeMethod);
    public function hasWriteMethod();
    public function getValue($target);
    public function setValue($target,$value);
    public function convertIfNeed($value);
}

class PropertyDescImpl implements PropertyDesc {
    protected $propertyName_ = null;
    protected $propertyType_ = null;
    protected $readMethod_ = null;
    protected $writeMethod_ = null;
    protected $beanDesc_ = null;
    public function PropertyDescImpl($propertyName=null,
                                       $propertyType,
                                       $readMethod,
                                       $writeMethod,
                                       BeanDesc $beanDesc) {
        if ($propertyName == null) {
            throw new EmptyRuntimeException("propertyName");
        }
        $this->propertyName_ = $propertyName;
        $this->propertyType_ = $propertyType;
        $this->readMethod_ = $readMethod;
        $this->writeMethod_ = $writeMethod;
        $this->beanDesc_ = $beanDesc;
    }
    public final function getPropertyName() {
        return $this->propertyName_;
    }
    public final function getPropertyType() {
        return $this->propertyType_;
    }
    public final function getReadMethod() {
        return $this->readMethod_;
    }
    public final function setReadMethod($readMethod) {
        $this->readMethod_ = $readMethod;
    }
    public final function hasReadMethod() {
        return $this->readMethod_ != null;
    }
    public final function getWriteMethod() {
        return $this->writeMethod_;
    }
    public final function setWriteMethod($writeMethod) {
        $this->writeMethod_ = $writeMethod;
    }
    public final function hasWriteMethod() {
        return $this->writeMethod_ != null;
    }
    public final function getValue($target) {
        return MethodUtil::invoke($this->readMethod_,$target, null);
    }
    public function setValue($target,$value) {
        try {
            MethodUtil::invoke($this->writeMethod_,$target, $value);
        } catch (Exception $t) {
            throw new IllegalPropertyRuntimeException(
                    $this->beanDesc_->getBeanClass(), $this->propertyName_, $t);
        }
    }
    public final function getBeanDesc() {
        return $this->beanDesc_;
    }
    public final function __toString() {
        $buf = "";
        $buf .= "propertyName=";
        $buf .= $this->propertyName_;
        $buf .= ",propertyType=";
        $buf .= $this->propertyType_ != null ? $this->propertyType_->getName() : "null";
        $buf .= ",readMethod=";
        $buf .= $this->readMethod_ != null ? $this->readMethod_->getName() : "null";
        $buf .= ",writeMethod=";
        $buf .= $this->writeMethod_ != null ? $this->writeMethod_->getName() : "null";
        return $buf;
    }
    public function convertIfNeed($arg) {
    }
}

final class ConstructorUtil {
    private function ConstructorUtil() {
    }
    public static function newInstance($refClass,$args,$componentDef=null){
        try {
            if($componentDef != null and 
               $componentDef->getAspectDefSize()>0){
               return AopProxyUtil::getEnhancedClass($componentDef,$args); 
            }
            $cmd = "return new " . $refClass->getName() . "(";
            if(count($args) == 0){
                $cmd = $cmd . ");";
                return eval($cmd);
            }
            $strArg=array();
            for($i=0;$i<count($args);$i++){
                array_push($strArg,"\$args[" . $i . "]");
            }
            $cmd = $cmd . implode(',',$strArg) . ");";
            return eval($cmd);
        }catch(Exception $e){
            throw $e;
        }
    }
}

class AopProxyUtil {
    private function AopProxyUtil() {
    }
    public static function getEnhancedClass(ComponentDef $componentDef,$args) {
        $parameters = array();
        $parameters[ContainerConstants::COMPONENT_DEF_NAME] = $componentDef;
        $proxy = new AopProxy($componentDef->getComponentClass(),
                               AopProxyUtil::getAspects($componentDef),
                               $parameters);
        return $proxy->create("",$args);
    }
    private static function getAspects(ComponentDef $componentDef) {
        $size = $componentDef->getAspectDefSize();
        $aspects = array();
        for ($i = 0; $i < $size; ++$i) {
            array_push($aspects,$componentDef->getAspectDef($i)->getAspect());
        }
        return $aspects;
    }
}

final class AopProxy {
	private $log_;
    private $targetClass_;
    private $enhancedClass_;
    private $defaultPointcut_;
    private $parameters_;
    private $methodInterceptorsMap_;
    public function AopProxy($targetClass,$aspects,$parameters=null) {
		$this->log_ = S2Logger::getLogger(get_class($this));
        $this->parameters_ = $parameters;
        if($targetClass instanceof ReflectionClass){ 
            $this->setTargetClass($targetClass);
        }else{
            $this->setTargetClass(new ReflectionClass($targetClass));
        }
        $this->setAspects($aspects);
    }
    private function setTargetClass($targetClass) {
        $this->targetClass_ = $targetClass;
        $this->defaultPointcut_ = new PointcutImpl($targetClass);
    }
    private function setAspects($aspects) {
        if ($aspects == null || count($aspects) == 0) {
            throw new EmptyRuntimeException("aspects");
        }
        for ($i = 0; $i < count($aspects); ++$i) {
            $aspect = $aspects[$i];
            if ($aspect->getPointcut() == null) {
                $aspect->setPointcut($this->defaultPointcut_);
            }
        }
        $methods = $this->targetClass_->getMethods();
        $this->methodInterceptorsMap_ = array();
        for ($i = 0;$i < count($methods); ++$i) {
        	if(!AopProxy::isApplicableAspect($methods[$i])){
        		$this->log_->info($this->targetClass_->getName()."::".
        		                   $methods[$i]->getName() ."() is a constructor or a static method. ignored.",__METHOD__);
                continue;        		                  
        	}
            $interceptorList = array();
            for ($j = 0; $j < count($aspects); ++$j) {
                $aspect = $aspects[$j];
                if ($aspect->getPointcut()->isApplied($methods[$i]->getName())) {
                    array_push($interceptorList,$aspect->getMethodInterceptor());
                }else{
                    $this->log_->info("no pointcut defined for " . 
                        $this->targetClass_->getName() . "::" .
                        $methods[$i]->getName() . "()",__METHOD__);
                }
            }
            if(count($interceptorList) > 0){
                $this->methodInterceptorsMap_[$methods[$i]->getName()] = $interceptorList;
            }
        }
    }
    public function getEnhancedClass() {
        return $this->enhancedClass_;
    }
    public function create($argTypes=null,$args=null) {
        if($this->targetClass_->isFinal()){
        	throw new S2RuntimeException('ESSR0017',
        	                               array("cannot aspect. target class [{$this->targetClass_->getName()}] is final class. "));
        }
        $this->enhancedClass_ = UuCallAopProxyFactory::create(
                                    $this->targetClass_,
                                    $this->methodInterceptorsMap_,
                                    $args,
                                    $this->parameters_);
        return $this->enhancedClass_;
    }
    public static function isApplicableAspect(ReflectionMethod $method) {
    	return ! $method->isStatic() and ! $method->isConstructor();
    }
}

final class AutoConstructorAssembler
    extends AbstractConstructorAssembler {
    public function AutoConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble(){
        $args = array();
        $refMethod = $this->getComponentDef()->getConcreteClass()->getConstructor();
        if($refMethod != null){
            $args = $this->getArgs($this->getComponentDef()->getConcreteClass()->getConstructor()->getParameters());
        }
        return ConstructorUtil::newInstance($this->getComponentDef()->getConcreteClass(), $args,$this->getComponentDef());
    }
}

interface Aspect {
    public function getMethodInterceptor();
    public function getPointcut();
    public function setPointcut(Pointcut $pointcut);
}

class AspectImpl implements Aspect {
    private $methodInterceptor_;
    private $pointcut_;
    public function AspectImpl(MethodInterceptor $methodInterceptor,Pointcut $pointcut) {
        $this->methodInterceptor_ = $methodInterceptor;
        $this->pointcut_ = $pointcut;
    }
    public function getMethodInterceptor() {
        return $this->methodInterceptor_;
    }
    public function getPointcut() {
        return $this->pointcut_;
    }
    public function setPointcut(Pointcut $pointcut) {
        $this->pointcut_ = $pointcut;
    }
}

class UuCallAopProxyFactory {
    private function UuCallAopProxyFactory() {}
    static function create($targetClass,$map,$args,$params=null){
        $log = S2Logger::getLogger('UuCallAopProxyFactory');
        if(ClassUtil::hasMethod($targetClass,'__call')){
            $log->info("target class has __call(). ignore aspect.",__METHOD__);
            return ConstructorUtil::newInstance($targetClass,$args);
        }
        $concreteClassName = 'UuCallAopProxy' . $targetClass->getName() . 'EnhancedByS2AOP';
        if(class_exists($concreteClassName,false)){
            return new $concreteClassName($targetClass,$map,$args,$params);
        }
        if(!$targetClass->isUserDefined()){
            return new UuCallAopProxy($targetClass,$map,$args,$params);
        }
        $classSrc = ClassUtil::getClassSource(new ReflectionClass('UuCallAopProxy'));
        $interfaces = ClassUtil::getInterfaces($targetClass); 
        if(count($interfaces) == 0){
            return new UuCallAopProxy($targetClass,$map,$args,$params);
        }
        $addMethodSrc = array();
        $interfaceNames = array();
        foreach ($interfaces as $interface){
            $interfaceSrc = ClassUtil::getSource($interface);
            $methods = $interface->getMethods();
            $unApplicable = false;
            foreach ($methods as $method){
                if($method->getDeclaringClass()->getName() == $interface->getName()){
                    if(AopProxy::isApplicableAspect($method)){
                        array_push($addMethodSrc,UuCallAopProxyFactory::getMethodDefinition($method,$interfaceSrc));
                    }else{
                        $unApplicable=true;	
                        break;
                    }
                }
            }
            if(!$unApplicable){
                array_push($interfaceNames,$interface->getName());
            }else{
                $log->info("interface [".$interface->getName()."] is unapplicable. not implemented.",__METHOD__);
            }
        }          
        if(count($interfaceNames)>0){
        	$implLine = " implements " . implode(',',$interfaceNames) . ' {';
        }else{
        	$implLine = ' {';
        }
        $srcLine = str_replace('UuCallAopProxy',$concreteClassName,$classSrc[0]);
        $srcLine = str_replace('{',$implLine,$srcLine);
        for($i=1;$i<count($classSrc)-1;$i++){
            $srcLine .= str_replace('UuCallAopProxy',$concreteClassName,$classSrc[$i]);
        }
        foreach($addMethodSrc as $methodSrc){
            $srcLine .= $methodSrc . "\n";
        }
        $srcLine .= "}\n";
        eval($srcLine);
        return new $concreteClassName($targetClass,$map,$args,$params);
    }
    private static function getMethodDefinition($refMethod,$interfaceSrc){
        $def = MethodUtil::getSource($refMethod,$interfaceSrc);        
        $defLine = trim(implode(' ',$def));
        $defLine = preg_replace("/\;$/","",$defLine);
        $defLine = preg_replace("/abstract\s/","",$defLine);
        $defLine .= " {";
        if(preg_match("/\((.*)\)/",$defLine,$regs)){
            $argLine = $regs[1];
        }
        $argsTmp = split('[ ,]',$argLine);
        $args = array();
        foreach($argsTmp as $item){
            if(preg_match('/^\$/',$item)){
                array_push($args,$item);
            }
            if(preg_match('/^\&(.+)/',$item,$regs)){
                array_push($args,$regs[1]);
            }
        }
        $argLine = implode(',',$args);
        $defLine .= ' return $this->__call(\'' . $refMethod->getName() .
                    '\',array(' . $argLine . ')); }';
        return $defLine;
    }
}

final class ClassUtil {
    private function ClassUtil() {
    }
    static function getClassSource($refClass){
        if(!is_readable($refClass->getFileName())){
            throw new S2RuntimeException('ESSR1006',array($refClass->getFileName()));
        }
        $ret = array();
        $lines = file($refClass->getFileName());
        $start = $refClass->getStartLine();
        $end   = $refClass->getEndLine();
        for($i=$start-1;$i<$end;$i++){
            array_push($ret,$lines[$i]);
        }
        return $ret;
    }
    static function getSource($refClass){
        if(!is_readable($refClass->getFileName())){
            throw new S2RuntimeException('ESSR1006',array($refClass->getFileName()));
        }
        $ret = array();
        return file($refClass->getFileName());
    }
	public static function getMethod(
		                          ReflectionClass $clazz,
		                          $methodName) {
        try{
            return $clazz->getMethod($methodName);
        }catch(ReflectionException $e){
            throw new NoSuchMethodRuntimeException($clazz,$methodName,$e);
        }
	}
	public static function hasMethod(
		                          ReflectionClass $clazz,
		                          $methodName) {
        try{
            $m = $clazz->getMethod($methodName);
            return true;
        }catch(ReflectionException $e){
            return false;
        }
	}
	public static function getInterfaces(ReflectionClass $clazz){
        $interfaces = $clazz->getInterfaces();
        if($clazz->isInterface()){
            array_push($interfaces,$clazz);
        }       
        return $interfaces;
	}
}

class UuCallAopProxy {
    private $parameters_;
    private $methodInterceptorsMap_;
    private $target_ = null;
    private $targetClass_;
    function UuCallAopProxy($targetClass,$map,$args,$params) {
        $this->targetClass_=$targetClass;
        $this->methodInterceptorsMap_ = $map;
        $this->parameters_ = $params;
        if(!$this->targetClass_->isInterface() && !$this->targetClass_->isAbstract()){
            $this->target_ = ConstructorUtil::newInstance($this->targetClass_,$args);
        }
    }
    function __call($name,$args){
        if(array_key_exists($name,$this->methodInterceptorsMap_)){
            $methodInvocation = new S2MethodInvocationImpl(
                                    $this->target_,
                                    $this->targetClass_,
                                    $this->targetClass_->getMethod($name),
                                    $args,
                                    $this->methodInterceptorsMap_[$name],
                                    $this->parameters_);
            return $methodInvocation->proceed();
        }else{
        	if(!is_object($this->target_)){
        		if($this->targetClass_->isInterface()){
        			$msg = "target class [{$this->targetClass_->getName()}] is interface. ";
        		}else{
        			$msg = "target class [{$this->targetClass_->getName()}] ";
        		}
        		throw new S2RuntimeException('ESSR0043',array($name,$msg));
        	}
            return MethodUtil::invoke($this->targetClass_->getMethod($name),
                                       $this->target_,
                                       $args);
        }
    }
}

class PropertyNotFoundRuntimeException
    extends S2RuntimeException {
    private $targetClass_;
    private $propertyName_;
    public function PropertyNotFoundRuntimeException(
        $componentClass,
        $propertyName) {
        parent::__construct("ESSR0065",array($componentClass->getName(), $propertyName));
        $this->targetClass_ = $componentClass;
        $this->propertyName_ = $propertyName;
    }
    public function getTargetClass() {
        return $this->targetClass_;
    }
    public function getPropertyName() {
        return $this->propertyName_;
    }
}

final class MethodUtil {
    private function MethodUtil() {
    }
    public static function invoke($method,$target,$args) {
        try {
            if(!is_array($args)){
                $args = array($args);
            }
            if(count($args) == 0){
                return $method->invoke($target,$args);
            }
            $strArg=array();
            for($i=0;$i<count($args);$i++){
                array_push($strArg,"\$args[" . $i . "]");
            }
            $methodName = $method->getName();
            $cmd = 'return $target->' . $methodName . '('.
                   implode(',',$strArg) . ");";
            return eval($cmd);
        }catch(Exception $e){
            throw $e;
        }
    }
    public static function isAbstract(ReflectionMethod $method) {
        return $method->isAbstract();
    }
    public static function getSource(ReflectionMethod $method,
                                        $src = null) {
        if($src == null){
        	$src = ClassUtil::getSource($method->getDeclaringClass());
        }
        $def = array();
        $start = $method->getStartLine();
        $end = $method->getEndLine();
        for($i=$start-1;$i<$end;$i++){
            array_push($def,$src[$i]);
        }
        return $def;
    }
}

?>
