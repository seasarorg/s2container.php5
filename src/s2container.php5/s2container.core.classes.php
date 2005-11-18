<?php
interface S2Container_MethodInterceptor {
    function invoke(S2Container_MethodInvocation $invocation);
}
abstract class S2Container_AbstractInterceptor implements S2Container_MethodInterceptor {
    public function createProxy($targetClass) {
        $aspect = new S2Container_AspectImpl($this, new S2Container_PointcutImpl(array(".*")));
        $proxy = S2Container_AopProxyFactory::create(
                   null,
                   $targetClass,
                   array($aspect),
                   array());
        return $proxy;
    }
    protected function getTargetClass(S2Container_MethodInvocation $invocation) {
        if ($invocation instanceof S2Container_S2MethodInvocation) {
            return $invocation->getTargetClass();
        }
        $thisClass = new ReflectionClass($invocation->getThis());
        $superClass = $thisClass->getParentClass();
        if ($superClass == null) {
            $ifs = $thisClass->getInterfaces();
            return $ifs[0];
        } else {
            return $superClass;
        }
    }
    protected function getComponentDef(S2Container_MethodInvocation $invocation) {
        if ($invocation instanceof S2Container_S2MethodInvocation) {
            return $invocation->getParameter(S2Container_ContainerConstants::COMPONENT_DEF_NAME);
        }
        return null;
    }
}
interface S2Container_MetaDefAware {
    public function addMetaDef(S2Container_MetaDef $metaDef);
    public function getMetaDefSize();
    public function getMetaDef($index);
    public function getMetaDefs($name);
}
interface S2Container extends S2Container_MetaDefAware{
    public function getComponent($componentKey);
	public function findComponents($componentKey);
    public function injectDependency($outerComponent,$componentName="");
    public function register($component, $componentName="");
    public function getComponentDefSize();
    public function getComponentDef($index);
	public function findComponentDefs($componentKey);
    public function hasComponentDef($componentKey);
    public function hasDescendant($path);
    public function getDescendant($path);
    public function registerDescendant(S2Container $descendant);
    public function includeChild(S2Container $child);
    public function getChildSize();
    public function getChild($index);
    public function init();
    public function destroy();
    public function reconstruct($mode=S2Container_ComponentDef::RECONSTRUCT_NORMAL);
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
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
        $this->root_ = $this;
        $componentDef = new S2Container_SimpleComponentDef($this, S2Container_ContainerConstants::CONTAINER_NAME);
        $this->componentDefMap_[S2Container_ContainerConstants::CONTAINER_NAME] = $componentDef;
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
	public function findComponents($componentKey) {
	    $componentDefs = $this->findComponentDefs($componentKey);
	    $components = array();
	    foreach($componentDefs as $componentDef) {
	        array_push($components,$componentDef->getComponent());
	    }
		return $components;
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
        if($component instanceof S2Container_ComponentDef){
            $this->register0($component);
            array_push($this->componentDefList_,$component);
        }else if(is_object($component)){
            $this->register(new S2Container_SimpleComponentDef($component,trim($componentName)));
        }else {
            $this->register(new S2Container_ComponentDefImpl($component, trim($componentName)));
        }
    }
    private function register0(S2Container_ComponentDef $componentDef) {
        if ($componentDef->getContainer() == null) {
            $componentDef->setContainer($this);
        }    
        $this->registerByClass($componentDef);
        $this->registerByName($componentDef);
    }
    private function registerByClass(S2Container_ComponentDef $componentDef) {
        $classes = $this->getAssignableClasses($componentDef->getComponentClass());
        for ($i = 0; $i < count($classes); ++$i) {
            $this->registerMap($classes[$i], $componentDef);
        }
    }
    private function registerByName(S2Container_ComponentDef $componentDef) {
        $componentName = $componentDef->getComponentName();
        if ($componentName != "") {
            $this->registerMap($componentName, $componentDef);
        }
    }
    private function registerMap($key, S2Container_ComponentDef $componentDef) {
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
        		throw new S2Container_ComponentNotFoundRuntimeException($key);
        	}
            return $this->componentDefList_[$key];
        }
        if(is_object($key)){
            $key = get_class($key);
        }
        $cd = $this->internalGetComponentDef($key);
        if ($cd == null) {
            throw new S2Container_ComponentNotFoundRuntimeException($key);
        }
        return $cd;
    }
	public function findComponentDefs($key){
		$cd = $this->internalGetComponentDef($key);
		if ($cd == null) {
			return array();
		}
		else if ($cd instanceof S2Container_TooManyRegistrationComponentDef) {
		    return $cd->getComponentDefs();
		}
		return array($cd);
    }
    private function internalGetComponentDef($key) {
        $cd = null;
        if(array_key_exists($key,$this->componentDefMap_)){
            $cd = $this->componentDefMap_[$key];
            if ($cd != null) {
                return $cd;
            }
        }
        if(preg_match("/(.+)".S2Container_ContainerConstants::NS_SEP."(.+)/",$key,$ret)){
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
        return $this->internalGetComponentDef($componentKey) != null;
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
            throw new S2Container_ContainerNotRegisteredRuntimeException($path);
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
    		throw new S2Container_ContainerNotRegisteredRuntimeException("Child:".$index);
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
    public function reconstruct($mode=S2Container_ComponentDef::RECONSTRUCT_NORMAL) {
        $c = $this->getChildSize();
        for ($i = 0; $i < $c; ++$i) {
            $this->getChild($i)->reconstruct($mode);
        }
        $componentDef = $this->componentDefMap_[S2Container_ContainerConstants::CONTAINER_NAME]->reconstruct($mode);
        $c = $this->getComponentDefSize();
        for ($i = 0; $i < $c; ++$i) {
            if($this->getComponentDef($i)->reconstruct($mode) and 
               $mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL){
                $this->registerByClass($this->getComponentDef($i));
            }
        }
    }
    public function getNamespace() {
        return $this->namespace_;
    }
    public function setNamespace($namespace) {
        $this->namespace_ = $namespace;
        $this->componentDefMap_[$this->namespace_] = 
            new S2Container_SimpleComponentDef($this,$this->namespace_);
    }
    public function getPath() {
        return $this->path_;
    }
    public function setPath($path) {
        $this->path_ = $path;
    }
    public function addMetaDef(S2Container_MetaDef $metaDef) {
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
            S2Container_ComponentDef $componentDef) {
        $cd = $this->componentDefMap_[$key];
        if ($cd instanceof S2Container_TooManyRegistrationComponentDef) {
            $cd->addComponentDef($componentDef);
        } else {
            $tmrcf = new S2Container_TooManyRegistrationComponentDefImpl($key);
            $tmrcf->addComponentDef($cd);
            $tmrcf->addComponentDef($componentDef);
            $this->componentDefMap_[$key] = $tmrcf;
        }
    }
}
final class S2Container_MetaDefSupport {
    private $metaDefs_ = array();
    private $container_;
    public function S2Container_MetaDefSupport($container=null) {
        if($container instanceof S2Container){
            $this->setContainer($container);
        }
    }
    public function addMetaDef(S2Container_MetaDef $metaDef) {
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
interface S2Container_ArgDefAware {
    public function addArgDef(S2Container_ArgDef $argDef);
    public function getArgDefSize();
    public function getArgDef($index);
}
interface S2Container_PropertyDefAware {
    public function addPropertyDef(S2Container_PropertyDef $propertyDef);
    public function getPropertyDefSize();
    public function getPropertyDef($index);
    public function hasPropertyDef($propertyName);
}
interface S2Container_InitMethodDefAware {
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef);
    public function getInitMethodDefSize();
    public function getInitMethodDef($index);
}
interface S2Container_DestroyMethodDefAware {
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef);
    public function getDestroyMethodDefSize();
    public function getDestroyMethodDef($index);
}
interface S2Container_AspectDefAware {
    public function addAspectDef(S2Container_AspectDef $aspectDef);
    public function getAspectDefSize();
    public function getAspectDef($index);
}
interface S2Container_ComponentDef
    extends
        S2Container_ArgDefAware,
        S2Container_PropertyDefAware,
        S2Container_InitMethodDefAware,
        S2Container_DestroyMethodDefAware,
        S2Container_AspectDefAware,
        S2Container_MetaDefAware {
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
    const RECONSTRUCT_NORMAL = 0;
    const RECONSTRUCT_FORCE = 1;
    public function reconstruct($mode=S2Container_ComponentDef::RECONSTRUCT_NORMAL);
}
class S2Container_SimpleComponentDef implements S2Container_ComponentDef {
    private $component_;
    private $componentClass_;
    private $componentClassName_;
    private $componentName_;
    private $container_;
    public function S2Container_SimpleComponentDef($component,$componentName="") {
        $this->component_ = $component;
        $this->componentClass_ = new ReflectionClass($component);
        $this->componentClassName_ = $this->componentClass_->getName();
        $this->componentName_ = $componentName;
    }
    public function getComponent() {
        return $this->component_;
    }
    public function injectDependency($outerComponent) {
        throw new S2Container_UnsupportedOperationException("injectDependency");
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
    public function addArgDef(S2Container_ArgDef $constructorArgDef) {
        throw new S2Container_UnsupportedOperationException("addArgDef");
    }
    public function addPropertyDef(S2Container_PropertyDef $propertyDef) {
        throw new S2Container_UnsupportedOperationException("addPropertyDef");
    }
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef) {
        throw new S2Container_UnsupportedOperationException("addInitMethodDef");
    }
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef) {
        throw new S2Container_UnsupportedOperationException("addDestroyMethodDef");
    }
    public function addAspectDef(S2Container_AspectDef $aspectDef) {
        throw new S2Container_UnsupportedOperationException("addAspectDef");
    }
    public function getArgDefSize() {
        throw new S2Container_UnsupportedOperationException("getArgDefSize");
    }
    public function getPropertyDefSize() {
        throw new S2Container_UnsupportedOperationException("getPropertyDefSize");
    }
    public function getInitMethodDefSize() {
        throw new S2Container_UnsupportedOperationException("getInitMethodDefSize");
    }
    public function getDestroyMethodDefSize() {
        throw new S2Container_UnsupportedOperationException("getDestroyMethodDefSize");
    }
    public function getAspectDefSize() {
        throw new S2Container_UnsupportedOperationException("getAspectDefSize");
    }
    public function getArgDef($index) {
        throw new S2Container_UnsupportedOperationException("getArgDef");
    }
    public function getPropertyDef($index) {
        throw new S2Container_UnsupportedOperationException("getPropertyDef");
    }
    public function hasPropertyDef($propertyName) {
        throw new S2Container_UnsupportedOperationException("hasPropertyDef");
    }
    public function getInitMethodDef($index) {
        throw new S2Container_UnsupportedOperationException("getInitMethodDef");
    }
    public function getDestroyMethodDef($index) {
        throw new S2Container_UnsupportedOperationException("getDestroyMethodDef");
    }
    public function getAspectDef($index) {
        throw new S2Container_UnsupportedOperationException("getAspectDef");
    }
    public function addMetaDef(S2Container_MetaDef $metaDef) {
        throw new S2Container_UnsupportedOperationException("addMetaDef");
    }
    public function getMetaDef($index) {
        throw new S2Container_UnsupportedOperationException("getMetaDef");
    }
    public function getMetaDefs($name) {
        throw new S2Container_UnsupportedOperationException("getMetaDefs");
    }
    public function getMetaDefSize() {
        throw new S2Container_UnsupportedOperationException("getMetaDefSize");
    }
    public function getExpression() {
        throw new S2Container_UnsupportedOperationException("getExpression");
    }
    public function setExpression($str) {
        throw new S2Container_UnsupportedOperationException("setExpression");
    }
    public function getInstanceMode() {
        throw new S2Container_UnsupportedOperationException("getInstanceMode");
    }
    public function setInstanceMode($instanceMode) {
        throw new S2Container_UnsupportedOperationException("setInstanceMode");
    }
    public function getAutoBindingMode() {
        throw new S2Container_UnsupportedOperationException("getAutoBindingMode");
    }
    public function setAutoBindingMode($autoBindingMode) {
        throw new S2Container_UnsupportedOperationException("setAutoBindingMode");
    }
    public function init() {}
    public function destroy() {}
    public function reconstruct($mode=S2Container_ComponentDef::RECONSTRUCT_NORMAL) {
        if($mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL){
            return false;
        }
        $this->componentClass_ = new ReflectionClass($this->componentClassName_);
        return true;
    }
}
interface S2Container_ContainerConstants {
    const INSTANCE_SINGLETON = "singleton";
    const INSTANCE_PROTOTYPE = "prototype";
    const INSTANCE_REQUEST = "request";
    const INSTANCE_SESSION = "session";
    const INSTANCE_OUTER = "outer";
    const AUTO_BINDING_AUTO = "auto";
    const AUTO_BINDING_CONSTRUCTOR = "constructor";
    const AUTO_BINDING_PROPERTY = "property";
    const AUTO_BINDING_NONE = "none";
    const NS_SEP = '\.';
    const CONTAINER_NAME = "container";
    const REQUEST_NAME = "request";
    const RESPONSE_NAME = "response";
    const SESSION_NAME = "session";
    const SERVLET_CONTEXT_NAME = "servletContext";
    const COMPONENT_DEF_NAME = "componentDef";
}
class S2Container_ComponentDefImpl implements S2Container_ComponentDef {
    private $componentClass_;
    private $componentClassName_;
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
    private $instanceMode_ = S2Container_ContainerConstants::INSTANCE_SINGLETON;
    private $autoBindingMode_ = S2Container_ContainerConstants::AUTO_BINDING_AUTO;
    private $componentDeployer_;
    public function S2Container_ComponentDefImpl($componentClass="", $componentName="") {
        if($componentClass instanceof ReflectionClass){
            $this->componentClass_ = $componentClass;
            $this->componentClassName_ = $componentClass->getName();
        }else{
            if(class_exists($componentClass) or
               interface_exists($componentClass)){
               $this->componentClass_ = new ReflectionClass($componentClass);
            }
            $this->componentClassName_ = $componentClass;
        }
        $this->componentName_ = $componentName;
        $this->argDefSupport_ = new S2Container_ArgDefSupport();
        $this->propertyDefSupport_ = new S2Container_PropertyDefSupport();
        $this->initMethodDefSupport_ = new S2Container_InitMethodDefSupport();
        $this->destroyMethodDefSupport_ = new S2Container_DestroyMethodDefSupport();
        $this->aspectDefSupport_ = new S2Container_AspectDefSupport();
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
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
    public function addArgDef(S2Container_ArgDef $argDef) {
        $this->argDefSupport_->addArgDef($argDef);
    }
    public function addPropertyDef(S2Container_PropertyDef $propertyDef) {
        $this->propertyDefSupport_->addPropertyDef($propertyDef);
    }
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef) {
        $this->initMethodDefSupport_->addInitMethodDef($methodDef);
    }
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef) {
        $this->destroyMethodDefSupport_->addDestroyMethodDef($methodDef);
    }
    public function addAspectDef(S2Container_AspectDef $aspectDef) {
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
        if (S2Container_InstanceModeUtil::isSingleton($instanceMode)
                || S2Container_InstanceModeUtil::isPrototype($instanceMode)
                || S2Container_InstanceModeUtil::isRequest($instanceMode)
                || S2Container_InstanceModeUtil::isSession($instanceMode)
                || S2Container_InstanceModeUtil::isOuter($instanceMode)) {
            $this->instanceMode_ = $instanceMode;
        } else {
            throw new S2Container_IllegalArgumentException($instanceMode);
        }
    }
    public function getAutoBindingMode() {
        return $this->autoBindingMode_;
    }
    public function setAutoBindingMode($autoBindingMode) {
        if (S2Container_AutoBindingUtil::isAuto($autoBindingMode)
                || S2Container_AutoBindingUtil::isConstructor($autoBindingMode)
                || S2Container_AutoBindingUtil::isProperty($autoBindingMode)
                || S2Container_AutoBindingUtil::isNone($autoBindingMode)) {
            $this->autoBindingMode_ = $autoBindingMode;
        } else {
            throw new S2Container_IllegalArgumentException(autoBindingMode);
        }
    }
    public function init() {
        $this->getComponentDeployer()->init();
    }
    public function destroy() {
        $this->getComponentDeployer()->destroy();
    }
    public function reconstruct($mode=S2Container_ComponentDef::RECONSTRUCT_NORMAL) {
        if($mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL and
           $this->componentClass_ != null){
            return false;
        }
        if(class_exists($this->componentClassName_) or
           interface_exists($this->componentClassName_)){
            $this->componentClass_ = new ReflectionClass($this->componentClassName_);
            return true;
        }
        return false;
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
    public function addMetaDef(S2Container_MetaDef $metaDef) {
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
            if($this->expression_ == null and 
               $this->componentClass_ == null){
                throw new S2Container_S2RuntimeException('ESSR1008',
                           array($this->componentName_,$this->componentClassName_));
            }
            $this->componentDeployer_ = S2Container_ComponentDeployerFactory::create($this);
        }
        return $this->componentDeployer_;
    }   
}
final class S2Container_ArgDefSupport {
    private $argDefs_ = array();
    private $container_;
    public function S2Container_ArgDefSupport() {
    }
    public function addArgDef(S2Container_ArgDef $argDef) {
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
final class S2Container_PropertyDefSupport {
    private $propertyDefs_ = array();
    private $propertyDefList_ = array();
    private $container_;
    public function S2Container_PropertyDefSupport() {
    }
    public function addPropertyDef(S2Container_PropertyDef $propertyDef) {
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
final class S2Container_InitMethodDefSupport {
    private $methodDefs_ = array();
    private $container_;
    public function S2Container_InitMethodDefSupport() {
    }
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef) {
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
final class S2Container_DestroyMethodDefSupport {
    private $methodDefs_ = array();
    private $container_;
    public function S2Container_DestroyMethodDefSupport() {
    }
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef) {
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
final class S2Container_AspectDefSupport {
    private $aspectDefs_ = array();
    private $container_;
    public function S2Container_AspectDefSupport() {
    }
    public function addAspectDef(S2Container_AspectDef $aspectDef) {
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
interface S2Container_ArgDef extends S2Container_MetaDefAware {
    public function getValue();
    public function getContainer();
    public function setContainer($container);
    public function getExpression();
    public function setExpression($str);
    public function setChildComponentDef(S2Container_ComponentDef $componentDef);
}
class S2Container_ArgDefImpl implements S2Container_ArgDef {
    private $value_;
    private $container_;
    private $expression_ = "";
    private $exp_ = null;
    private $childComponentDef_ = null;
    private $metaDefSupport_;
    public function S2Container_ArgDefImpl($value=null) {
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
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
            $this->exp_ = S2Container_EvalUtil::getExpression($this->expression_);
        }
    }
    public final function setChildComponentDef(S2Container_ComponentDef $componentDef) {
        if ($this->container_ != null) {
            $componentDef->setContainer($this->container_);
        }
        $this->childComponentDef_ = $componentDef;
    }
    public function addMetaDef(S2Container_MetaDef $metaDef) {
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
interface S2Container_PropertyDef extends S2Container_ArgDef {
    public function getPropertyName();
}
class S2Container_PropertyDefImpl extends S2Container_ArgDefImpl implements S2Container_PropertyDef {
    private $propertyName_;
    public function S2Container_PropertyDefImpl($propertyName=null, $value=null) {
        parent::__construct($value);
        if($propertyName != null){
            $this->propertyName_ = $propertyName;
        }
    }
    public function getPropertyName() {
        return $this->propertyName_;
    }
}
final class S2Container_EvalUtil {
    private function S2Container_EvalUtil() {
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
interface S2Container_Pointcut { 
    public function isApplied($methodName);
}
final class S2Container_PointcutImpl implements S2Container_Pointcut {
    private $methodNames_;
    private $patterns_;
    public function S2Container_PointcutImpl($target=null) {
        if ($target == null) {
            throw new S2Container_EmptyRuntimeException("targetClass");
        }
        if(is_array($target)){
            if (count($target) == 0) {
                throw new S2Container_EmptyRuntimeException("methodNames");
            }
            $this->setMethodNames($target);
        }else{
            if($target instanceof ReflectionClass){
                $this->setMethodNames($this->getMethodNames($target));
            }else{
                $this->setMethodNames($this->getMethodNames(
                                       new ReflectionClass($target)));
            }
        }
    }
    public function isApplied($methodName) {
        for ($i = 0;$i < count($this->methodNames_); ++$i) {
            if(preg_match("/".$this->methodNames_[$i]."/",$methodName)){
          	    return true;
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
interface S2Container_AspectDef extends S2Container_ArgDef {
    public function getAspect();
}
class S2Container_AspectDefImpl extends S2Container_ArgDefImpl implements S2Container_AspectDef {
    private $pointcut_;
    public function S2Container_AspectDefImpl($arg1=null,$arg2=null) {
        parent::__construct();
        if($arg1 instanceof S2Container_Pointcut){
            $this->pointcut_ = $arg1;
        }
        if($arg2 instanceof S2Container_Pointcut){
            $this->pointcut_ = $arg2;
        }
        if($arg1 instanceof S2Container_MethodInterceptor){
            $this->setValue($arg1);
        }
        if($arg2 instanceof S2Container_MethodInterceptor){
            $this->setValue($arg2);
        }
    }
    public function getAspect() {
        $interceptor = $this->getValue();
        return new S2Container_AspectImpl($interceptor, $this->pointcut_);
    }
}final class S2Container_ComponentDeployerFactory {
    private function S2Container_ComponentDeployerFactory() {
    }
    public static function create(S2Container_ComponentDef $componentDef) {
        if (S2Container_InstanceModeUtil::isSingleton($componentDef->getInstanceMode())) {
            return new S2Container_SingletonComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isPrototype($componentDef->getInstanceMode())) {
            return new S2Container_PrototypeComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isRequest($componentDef->getInstanceMode())) {
            return new S2Container_RequestComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isSession($componentDef->getInstanceMode())) {
            return new S2Container_SessionComponentDeployer($componentDef);
        } else {
            return new S2Container_OuterComponentDeployer($componentDef);
        }
    }
}
final class S2Container_InstanceModeUtil {
    private function S2Container_InstanceModeUtil() {
    }
    public static final function isSingleton($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_SINGLETON)
                == strtolower($mode);
    }
    public static final function isPrototype($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_PROTOTYPE)
                == strtolower($mode);
    }
    public static final function isRequest($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_REQUEST)
                == strtolower($mode);
    }
    public static final function isSession($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_SESSION)
                == strtolower($mode);
    }
    public static final function isOuter($mode) {
        return strtolower(S2Container_ContainerConstants::INSTANCE_OUTER)
                == strtolower($mode);
    }
}
interface S2Container_ComponentDeployer {
    public function deploy();
    public function injectDependency($outerComponent);
    public function init();
    public function destroy();
}
abstract class S2Container_AbstractComponentDeployer implements S2Container_ComponentDeployer {
    private $componentDef_;
    private $constructorAssembler_;
    private $propertyAssembler_;
    private $initMethodAssembler_;
    private $destroyMethodAssembler_;
    public function S2Container_AbstractComponentDeployer(S2Container_ComponentDef $componentDef) {
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
        if (S2Container_AutoBindingUtil::isAuto($autoBindingMode)) {
            $this->setupAssemblerForAuto();
        } else if (S2Container_AutoBindingUtil::isConstructor($autoBindingMode)) {
            $this->setupAssemblerForConstructor();
        } else if (S2Container_AutoBindingUtil::isProperty($autoBindingMode)) {
            $this->setupAssemblerForProperty();
        } else if (S2Container_AutoBindingUtil::isNone($autoBindingMode)) {
            $this->setupAssemblerForNone();
        } else {
            throw new S2Container_IllegalArgumentException($autoBindingMode);
        }
        $this->initMethodAssembler_ = new S2Container_DefaultInitMethodAssembler($this->componentDef_);
        $this->destroyMethodAssembler_ = new S2Container_DefaultDestroyMethodAssembler($this->componentDef_);
    }
    private function setupAssemblerForAuto() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new S2Container_AutoPropertyAssembler($this->componentDef_);
    }
    private function setupConstructorAssemblerForAuto() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new S2Container_ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new S2Container_AutoConstructorAssembler($this->componentDef_);
        }
    }
    private function setupAssemblerForConstructor() {
        $this->setupConstructorAssemblerForAuto();
        $this->propertyAssembler_ = new S2Container_ManualPropertyAssembler($this->componentDef_);
    }
    private function setupAssemblerForProperty() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ = new S2Container_ManualConstructorAssembler($this->componentDef_);
        }
        $this->propertyAssembler_ = new S2Container_AutoPropertyAssembler($this->componentDef_);
    }
    private function setupAssemblerForNone() {
        if ($this->componentDef_->getExpression() != null) {
            $this->constructorAssembler_ =
                new S2Container_ExpressionConstructorAssembler($this->componentDef_);
        }else if ($this->componentDef_->getArgDefSize() > 0) {
            $this->constructorAssembler_ =
                new S2Container_ManualConstructorAssembler($this->componentDef_);
        } else {
            $this->constructorAssembler_ =
                new S2Container_DefaultConstructorAssembler($this->componentDef_);
        }
        if ($this->componentDef_->getPropertyDefSize() > 0) {
            $this->propertyAssembler_ = new S2Container_ManualPropertyAssembler($this->componentDef_);
        } else {
            $this->propertyAssembler_ = new S2Container_DefaultPropertyAssembler($this->componentDef_);
        }
    }
}
class S2Container_SingletonComponentDeployer extends S2Container_AbstractComponentDeployer {
    private $component_;
    private $instantiating_ = false;
    public function S2Container_SingletonComponentDeployer(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function deploy() {
        if ($this->component_ == null) {
            $this->assemble();
        }
        return $this->component_;
    }
    public function injectDependency($component) {
        throw new S2Container_UnsupportedOperationException("injectDependency");
    }
    private function assemble() {
        if ($this->instantiating_) {
            throw new S2Container_CyclicReferenceRuntimeException(
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
final class S2Container_AutoBindingUtil {
    private function S2Container_AutoBindingUtil() {
    }
    public static final function isSuitable($classes) {
        if(is_array($classes)){
            for ($i = 0; $i < count($classes); ++$i) {
                if (!S2Container_AutoBindingUtil::isSuitable($classes[$i])) {
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
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_AUTO)
                == strtolower($mode);
    }
    public static final function isConstructor($mode) {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR)
                == strtolower($mode);
    }
    public static final function isProperty($mode) {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_PROPERTY)
                == strtolower($mode);
    }
    public static final function isNone($mode) {
        return strtolower(S2Container_ContainerConstants::AUTO_BINDING_NONE)
                == strtolower($mode);
    }
}
abstract class S2Container_AbstractAssembler {
    private $log_;
    private $componentDef_;
    public function S2Container_AbstractAssembler(S2Container_ComponentDef $componentDef) {
        $this->componentDef_ = $componentDef;
        $this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }
    protected final function getComponentDef() {
        return $this->componentDef_;
    }
    protected final function getBeanDesc($component=null) {
        if(!is_object($component)){
            return S2Container_BeanDescFactory::getBeanDesc(
                $this->getComponentDef()->getComponentClass());
        }
        return S2Container_BeanDescFactory::getBeanDesc(
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
                    S2Container_AutoBindingUtil::isSuitable($argTypes[$i]->getClass())){
                    $args[$i]= $this->getComponentDef()->getContainer()->getComponent($argTypes[$i]->getClass()->getName());
                }else{
                	if($argTypes[$i]->isOptional()){
                        $args[$i] = $argTypes[$i]->getDefaultValue();
                	}else{
                		$args[$i] = null;
                	}
                }
            } catch (S2Container_ComponentNotFoundRuntimeException $ex) {
                $this->log_->warn($ex->getMessage(),__METHOD__);
                $args[$i] = null;
            }
        }
        return $args;
    }
}
interface S2Container_ConstructorAssembler {
    public function assemble();
}
abstract class S2Container_AbstractConstructorAssembler extends S2Container_AbstractAssembler
        implements S2Container_ConstructorAssembler {
    public function S2Container_AbstractConstructorAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function assembleDefault() {
        $clazz = $this->getComponentDef()->getConcreteClass();
        if($this->getComponentDef() != null and 
           $this->getComponentDef()->getAspectDefSize()>0){
            return S2Container_AopProxyUtil::getProxyObject($this->getComponentDef(),$args); 
        }        
        return S2Container_ConstructorUtil::newInstance($clazz, null);
    }
}
class S2Container_ManualConstructorAssembler
    extends S2Container_AbstractConstructorAssembler {
    public function S2Container_ManualConstructorAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble(){
        $args = array();
        for ($i = 0; $i < $this->getComponentDef()->getArgDefSize(); ++$i) {
            try {
                $args[$i] = $this->getComponentDef()->getArgDef($i)->getValue();
            } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
                throw new S2Container_IllegalConstructorRuntimeException(
                    $this->getComponentDef()->getComponentClass(),
                    $cause);
            }
        }
        $beanDesc =
            S2Container_BeanDescFactory::getBeanDesc($this->getComponentDef()->getConcreteClass());
        return $beanDesc->newInstance($args,$this->getComponentDef());
    }
}
final class S2Container_S2Logger {
    private static $loggerMap_ = array();
    private $log_;
    private function S2Container_S2Logger($className) {
        $this->log_ = S2Container_S2LogFactory::getLog($className);
    }
    public static final function getLogger($className) {
        $logger = null;
        if(array_key_exists($className,S2Container_S2Logger::$loggerMap_)){
            $logger = S2Container_S2Logger::$loggerMap_[$className];
        }
        if ($logger == null) {
            $logger = new S2Container_S2Logger($className);
            S2Container_S2Logger::$loggerMap_[$className] = $logger;
        }
        return $logger->getLog();
    }
    private function getLog(){
        return $this->log_;
    }
}
class S2Container_S2LogFactory {
    private function LogFactory() {
    }
    public static function getLog($className){
        return new S2Container_SimpleLogger($className);
    }
}
class S2Container_SimpleLogger {
    private $className;
    const DEBUG = 1;
    const INFO  = 2;
    const WARN  = 3;
    const ERROR = 4;
    const FATAL = 5;
    function S2Container_SimpleLogger($className) {
        $this->className = $className;
        if(!defined('S2CONTAINER_PHP5_LOG_LEVEL')){
            define('S2CONTAINER_PHP5_LOG_LEVEL',3);
        }
    }
    private function cli($level,$msg="",$methodName=""){
        switch($level){
            case S2Container_SimpleLogger::DEBUG :
                $logLevel = "DEBUG";
                break;    
            case S2Container_SimpleLogger::INFO :
                $logLevel = "INFO";
                break;    
            case S2Container_SimpleLogger::WARN :
                $logLevel = "WARN";
                break;    
            case S2Container_SimpleLogger::ERROR :
                $logLevel = "ERROR";
                break;    
            case S2Container_SimpleLogger::FATAL :
                $logLevel = "FATAL";
                break;    
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL <= $level){
            printf("[%-5s] %s - %s\n",$logLevel,$methodName,$msg);
        }
    }
    public function debug($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::DEBUG,$msg,$methodName);
    }
    public function info($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::INFO,$msg,$methodName);
    }
    public function warn($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::WARN,$msg,$methodName);
    }
    public function error($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::ERROR,$msg,$methodName);
    }
    public function fatal($msg="",$methodName=""){
        $this->cli(S2Container_SimpleLogger::FATAL,$msg,$methodName);
    }
}
interface S2Container_PropertyAssembler {
    public function assemble($component);
}
abstract class S2Container_AbstractPropertyAssembler
    extends S2Container_AbstractAssembler
    implements S2Container_PropertyAssembler {
    public function S2Container_AbstractPropertyAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function getValue(S2Container_PropertyDef $propertyDef, $component) {
        try {
            return $propertyDef->getValue();
        } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
            throw new S2Container_IllegalPropertyRuntimeException(
                $this->getComponentClass($component),
                $propertyDef->getPropertyName(),
                $cause);
        }
    }
    protected function setValue(
        S2Container_PropertyDesc $propertyDesc,
        $component,
        $value){
        if ($value == null) {
            return;
        }
        try {
            $propertyDesc->setValue($component,$value);
        } catch (Exception $ex) {
            throw new S2Container_IllegalPropertyRuntimeException(
                $this->getComponentDef()->getComponentClass(),
                $propertyDesc->getPropertyName(),
                $ex);
        }
    }
}
class S2Container_ManualPropertyAssembler extends S2Container_AbstractPropertyAssembler {
    public function S2Container_ManualPropertyAssembler(S2Container_ComponentDef $componentDef) {
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
            }catch(S2Container_PropertyNotFoundRuntimeException $e1){
                try{
                	$propDesc =
                        $beanDesc->getPropertyDesc('__set');
                    $propDesc->setSetterPropertyName($propDef->getPropertyName());    
                }catch(S2Container_PropertyNotFoundRuntimeException $e2){
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
class S2Container_AutoPropertyAssembler extends S2Container_ManualPropertyAssembler {
    private $log_;
    public function S2Container_AutoPropertyAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
        $this->log_ = S2Container_S2Logger::getLogger(get_class($this));
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
                S2Container_AutoBindingUtil::isSuitable($propDesc->getPropertyType())) {
                try {
                    $value = $container->getComponent($propDesc->getPropertyType()->getName());
                } catch (S2Container_ComponentNotFoundRuntimeException $ex) {
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
interface S2Container_MethodAssembler {
    public function assemble($component);
}
abstract class S2Container_AbstractMethodAssembler
    extends S2Container_AbstractAssembler
    implements S2Container_MethodAssembler {
    public function S2Container_AbstractMethodAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    protected function invoke(
        S2Container_BeanDesc $beanDesc,
        $component,
        S2Container_MethodDef $methodDef) {
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
            } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
                throw new S2Container_IllegalMethodRuntimeException(
                    $this->getComponentClass($component),
                    $methodName,
                    $cause);
            }
            if ($method != null) {
                S2Container_MethodUtil::invoke($method, $component, $args);
            } else {
                $this->invoke0($beanDesc,$component,$methodName,$args);
            }
        } else {
            $this->invokeExpression($component,$expression);
        }
    }
    private function invokeExpression($component,$expression) {
    	$exp = S2Container_EvalUtil::addSemiColon($expression);
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
                if(!S2Container_AutoBindingUtil::isSuitable($param->getClass())){
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
        S2Container_BeanDesc $beanDesc,
        $component,
        $methodName,
        $args){
        try {
            $beanDesc->invoke($component,$methodName,$args);
        } catch (Exception $ex) {
            throw new S2Container_IllegalMethodRuntimeException(
                $this->getComponentClass($component),
                $methodName,
                $ex);
        }
    }
}
class S2Container_DefaultInitMethodAssembler extends S2Container_AbstractMethodAssembler {
    public function S2Container_DefaultInitMethodAssembler(S2Container_ComponentDef $componentDef) {
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
class S2Container_DefaultDestroyMethodAssembler extends S2Container_AbstractMethodAssembler {
    public function S2Container_DefaultDestroyMethodAssembler(S2Container_ComponentDef $componentDef) {
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
class S2Container_S2RuntimeException extends Exception {
    private $messageCode_;
    private $args_;
    private $message_;
    private $simpleMessage_;
    public function S2Container_S2RuntimeException(
        $messageCode,
        $args = null,
        $cause = null) {
        $cause instanceof Exception ? 
            $msg = $cause->getMessage() . "\n" :
            $msg = "";
        $msg .= S2Container_MessageUtil::getMessageWithArgs($messageCode,$args);
        parent::__construct($msg);
    }
}
class S2Container_ComponentNotFoundRuntimeException extends S2Container_S2RuntimeException {
    private $componentKey_;
    public function S2Container_ComponentNotFoundRuntimeException($componentKey) {
        parent::__construct("ESSR0046", array($componentKey));
        $this->componentKey_ = $componentKey;
    }
    public function getComponentKey() {
        return $this->componentKey_;
    }
}
final class S2Container_BeanDescFactory {
    private static $beanDescCache_ = array();
    private function S2Container_BeanDescFactory() {
    }
    public static function getBeanDesc(ReflectionClass $clazz) {
    	if(array_key_exists($clazz->getName(),S2Container_BeanDescFactory::$beanDescCache_)){
            $beanDesc = S2Container_BeanDescFactory::$beanDescCache_[$clazz->getName()];
    	}else{
            $beanDesc = new S2Container_BeanDescImpl($clazz);
            S2Container_BeanDescFactory::$beanDescCache_[$clazz->getName()] = $beanDesc;
        }
        return $beanDesc;
    }
}
interface S2Container_BeanDesc {
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
final class S2Container_BeanDescImpl implements S2Container_BeanDesc {
    private static $EMPTY_ARGS = array();
    private $beanClass_;
    private $constructors_;
    private $propertyDescCache_ = array();
    private $propertyDescCacheIndex_ = array();
    private $methodsCache_ = array();
    private $fieldCache_ = array();
    private $constCache_ = array();
    public function S2Container_BeanDescImpl(ReflectionClass $beanClass) {
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
                throw new S2Container_PropertyNotFoundRuntimeException(
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
            throw new S2Container_PropertyNotFoundRuntimeException(
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
            throw new S2Container_FieldNotFoundRuntimeException($this->beanClass_, $fieldName);
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
            throw new S2Container_ConstantNotFoundRuntimeException($this->beanClass_, $constName);
        }
        return $constant;
    }
    public function newInstance($args,$componentDef=null){
        if($componentDef != null and 
           $componentDef->getAspectDefSize()>0){
            return S2Container_AopProxyUtil::getProxyObject($componentDef,$args); 
        }
        return S2Container_ConstructorUtil::newInstance($this->beanClass_, $args);
    }
    public function invoke($target,$methodName,$args) {
        $method = $this->getMethods($methodName);
        return S2Container_MethodUtil::invoke($method,$target,$args);
    }
    public function getSuitableConstructor() {
    	return $this->constructors_;
    }
    public function getMethods($methodName){
        if(array_key_exists($methodName,$this->methodsCache_)){
            $methods = $this->methodsCache_[$methodName];
        }else{
            throw new S2Container_MethodNotFoundRuntimeException($this->beanClass_, $methodName, null);
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
    private function addPropertyDesc(S2Container_PropertyDesc $propertyDesc) {
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
				new S2Container_PropertyDescImpl(
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
                    new S2Container_UuSetPropertyDescImpl(
                        $propertyName,
                        null,
                        null,
                        $writeMethod,
                        $this);
            }else{
                $propertyTypes = $writeMethod->getParameters();
                $propDesc =
                    new S2Container_PropertyDescImpl(
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
interface S2Container_PropertyDesc {
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
class S2Container_PropertyDescImpl implements S2Container_PropertyDesc {
    protected $propertyName_ = null;
    protected $propertyType_ = null;
    protected $readMethod_ = null;
    protected $writeMethod_ = null;
    protected $beanDesc_ = null;
    public function S2Container_PropertyDescImpl($propertyName=null,
                                       $propertyType,
                                       $readMethod,
                                       $writeMethod,
                                       S2Container_BeanDesc $beanDesc) {
        if ($propertyName == null) {
            throw new S2Container_EmptyRuntimeException("propertyName");
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
        return S2Container_MethodUtil::invoke($this->readMethod_,$target, null);
    }
    public function setValue($target,$value) {
        try {
            S2Container_MethodUtil::invoke($this->writeMethod_,$target, array($value));
        } catch (Exception $t) {
            throw new S2Container_IllegalPropertyRuntimeException(
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
class S2Container_AopProxyUtil {
    private function S2Container_AopProxyUtil() {
    }
    public static function getProxyObject(S2Container_ComponentDef $componentDef,$args) {
        $parameters = array();
        $parameters[S2Container_ContainerConstants::COMPONENT_DEF_NAME] = $componentDef;
        $target = null;
        if(!$componentDef->getComponentClass()->isInterface() and
           !$componentDef->getComponentClass()->isAbstract()){
            $target = S2Container_ConstructorUtil::newInstance($componentDef->getComponentClass(),$args);
        }
        $proxy = S2Container_AopProxyFactory::create(
                   $target,
                   $componentDef->getComponentClass(),
                   S2Container_AopProxyUtil::getAspects($componentDef),
                   $parameters);
        return $proxy;
    }
    private static function getAspects(S2Container_ComponentDef $componentDef) {
        $size = $componentDef->getAspectDefSize();
        $aspects = array();
        for ($i = 0; $i < $size; ++$i) {
            array_push($aspects,$componentDef->getAspectDef($i)->getAspect());
        }
        return $aspects;
    }
}
final class S2Container_ConstructorUtil {
    private function S2Container_ConstructorUtil() {
    }
    public static function newInstance($refClass,$args){
        if(! $refClass instanceof ReflectionClass){
            throw new S2Container_IllegalArgumentException('args[0] must be <ReflectionClass>');
        }
        $cmd = "return new " . $refClass->getName() . "(";
        if(count($args) == 0){
            $cmd = $cmd . ");";
            return eval($cmd);
        }
        $strArg=array();
        $c = count($args);
        for($i=0;$i<$c;$i++){
            array_push($strArg,"\$args[" . $i . "]");
        }
        $cmd = $cmd . implode(',',$strArg) . ");";
        return eval($cmd);
    }
}
final class S2Container_AopProxyFactory {
    private function __construct(){}
    public function create($target=null,
                           $targetClass=null,
                           $aspects=null,
                           $parameters=null) {
        //$log = S2Container_S2Logger::getLogger('S2Container_AopProxyFactor');
        if(!$targetClass instanceof ReflectionClass){ 
        	if(is_string($targetClass)){
                $targetClass = new ReflectionClass($targetClass);
        	}else if(is_object($target)){
                $targetClass = new ReflectionClass($target);
            }else{
                throw new S2Container_S2RuntimeException('ESSR1010',array($target,$targetClass));
            }
        }
        if(!$targetClass->isUserDefined() or
           S2Container_ClassUtil::hasMethod($targetClass,'__call')){
            //$log->info("target class has __call(). ignore aspect.",__METHOD__);
            return $target;
        }
        $methodInterceptorsMap = S2Container_AopProxyFactory::creatMethodInterceptorsMap($targetClass,$aspects);
        $interfaces = S2Container_ClassUtil::getInterfaces($targetClass); 
        if(count($interfaces) == 0){
            return new S2Container_DefaultAopProxy($target,
                                                   $targetClass,
                                                   $methodInterceptorsMap,
                                                   $parameters);
        }
        $concreteClassName = S2Container_AopProxyGenerator::generate(
                                                $target,
                                                $targetClass,
                                                $parameters);
        return new $concreteClassName($target,$targetClass,$methodInterceptorsMap,$parameters);
    }
    private function creatMethodInterceptorsMap($targetClass,$aspects) {
        if ($aspects == null || count($aspects) == 0) {
            throw new S2Container_EmptyRuntimeException("aspects");
        }
        $defaultPointcut = new S2Container_PointcutImpl($targetClass);
        $c = count($aspects);
        for ($i = 0; $i < $c; ++$i) {
            if ($aspects[$i]->getPointcut() == null) {
                $aspects[$i]->setPointcut($defaultPointcut);
            }
        }
        $methods = $targetClass->getMethods();
        $methodInterceptorsMap = array();
        $o = count($methods);
        for ($i = 0;$i < $o; ++$i) {
            if(!S2Container_AopProxyFactory::isApplicableAspect($methods[$i])){
                //$log->info($this->targetClass_->getName()."::".
                //           $methods[$i]->getName() ."() is a constructor or a static method. ignored.",__METHOD__);
                continue;
            }
            $interceptorList = array();
            $p = count($aspects);
            for ($j = 0; $j < $p; ++$j) {
                $aspect = $aspects[$j];
                if ($aspects[$j]->getPointcut()->isApplied($methods[$i]->getName())) {
                    array_push($interceptorList,$aspects[$j]->getMethodInterceptor());
                }
            }
            if(count($interceptorList) > 0){
                $methodInterceptorsMap[$methods[$i]->getName()] = $interceptorList;
            }
        }
        return $methodInterceptorsMap;
    }
    public static function isApplicableAspect(ReflectionMethod $method) {
    	return $method->isPublic() and
               !$method->isStatic() and 
               !$method->isConstructor();
    }
}
class S2Container_AutoConstructorAssembler
    extends S2Container_AbstractConstructorAssembler {
    public function S2Container_AutoConstructorAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    public function assemble(){
        $args = array();
        $refMethod = $this->getComponentDef()->getConcreteClass()->getConstructor();
        if($refMethod != null){
            $args = $this->getArgs($this->getComponentDef()->getConcreteClass()->getConstructor()->getParameters());
        }
        if($this->getComponentDef() != null and 
           $this->getComponentDef()->getAspectDefSize()>0){
            return S2Container_AopProxyUtil::getProxyObject($this->getComponentDef(),$args); 
        }                
        return S2Container_ConstructorUtil::newInstance($this->getComponentDef()->getConcreteClass(), $args);
    }
}
interface S2Container_Aspect {
    public function getMethodInterceptor();
    public function getPointcut();
    public function setPointcut(S2Container_Pointcut $pointcut);
}
class S2Container_AspectImpl implements S2Container_Aspect {
    private $methodInterceptor_;
    private $pointcut_;
    public function S2Container_AspectImpl(S2Container_MethodInterceptor $methodInterceptor,S2Container_Pointcut $pointcut) {
        $this->methodInterceptor_ = $methodInterceptor;
        $this->pointcut_ = $pointcut;
    }
    public function getMethodInterceptor() {
        return $this->methodInterceptor_;
    }
    public function getPointcut() {
        return $this->pointcut_;
    }
    public function setPointcut(S2Container_Pointcut $pointcut) {
        $this->pointcut_ = $pointcut;
    }
}
final class S2Container_ClassUtil {
    private function S2Container_ClassUtil() {
    }
    static function getClassSource($refClass){
        if(!is_readable($refClass->getFileName())){
            throw new S2Container_S2RuntimeException('ESSR1006',array($refClass->getFileName()));
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
            throw new S2Container_S2RuntimeException('ESSR1006',array($refClass->getFileName()));
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
            throw new S2Container_NoSuchMethodRuntimeException($clazz,$methodName,$e);
        }
	}
	public static function hasMethod(
		                          ReflectionClass $clazz,
		                          $methodName) {
    	//return $clazz->hasMethod(methodName); php ver 5.1
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
class S2Container_DefaultAopProxy {
    private $methodInterceptorsMap_;
    private $parameters_;
    public $target_ = null;
    public $targetClass_;
    function __construct($target,$targetClass,$methodInterceptorsMap,$parameters) {
        $this->target_ = $target;
        $this->targetClass_ = $targetClass;
        $this->methodInterceptorsMap_ = $methodInterceptorsMap;
        $this->parameters_ = $parameters;
    }
    function __call($name,$args){
        if(array_key_exists($name,$this->methodInterceptorsMap_)){
            $methodInvocation = new S2Container_S2MethodInvocationImpl(
                                    $this->target_,
                                    $this->targetClass_,
                                    $this->targetClass_->getMethod($name),
                                    $args,
                                    $this->methodInterceptorsMap_[$name],
                                    $this->parameters_);
            return $methodInvocation->proceed();
        }else{
            if(!is_object($this->target_)){
                throw new S2Container_S2RuntimeException('ESSR1009',array($name,$this->targetClass_->getName()));
            }
            return S2Container_MethodUtil::invoke($this->targetClass_->getMethod($name),
                                                  $this->target_,
                                                  $args);
        }
    }
}
class S2Container_PropertyNotFoundRuntimeException
    extends S2Container_S2RuntimeException {
    private $targetClass_;
    private $propertyName_;
    public function S2Container_PropertyNotFoundRuntimeException(
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
final class S2Container_MethodUtil {
    private function S2Container_MethodUtil() {
    }
    public static function invoke($method,$target,$args=null) {
        if(! $method instanceof ReflectionMethod){
            throw new S2Container_IllegalArgumentException('args[0] must be <ReflectionMethod>');
        }
        if(! is_object($target)){
            throw new S2Container_IllegalArgumentException('args[1] must be <object>');
        }
        if(count($args) == 0){
            return $method->invoke($target,array());
        }
        $strArg=array();
        for($i=0;$i<count($args);$i++){
            array_push($strArg,"\$args[" . $i . "]");
        }
        $methodName = $method->getName();
        $cmd = 'return $target->' . $methodName . '('.
               implode(',',$strArg) . ");";
        return eval($cmd);
    }
    public static function isAbstract(ReflectionMethod $method) {
        return $method->isAbstract();
    }
    public static function getSource(ReflectionMethod $method,
                                        $src = null) {
        if($src == null){
        	$src = S2Container_ClassUtil::getSource($method->getDeclaringClass());
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
interface S2Container_Joinpoint {
    function getStaticPart();
    function getThis();
    function proceed();
}
interface S2Container_Invocation extends S2Container_Joinpoint{
    function getArguments();
}
interface S2Container_MethodInvocation extends S2Container_Invocation{
    function getMethod();
}
interface S2Container_S2MethodInvocation extends S2Container_MethodInvocation {
    function getTargetClass();
    function getParameter($name);
}
class S2Container_S2MethodInvocationImpl implements S2Container_S2MethodInvocation{
    private $interceptorIndex = 0;
    private $interceptors;
    private $method;
    private $methodArgs;
    private $parameters_;
    private $target;
    private $targetClass;
    function S2Container_S2MethodInvocationImpl(
        $target,
        $targetClass,
        $method,
        $methodArgs,
        $interceptors,
        $parameters=null) {
        $this->target = $target;
        $this->targetClass = $targetClass;
        $this->method = $method;
        $this->methodArgs = $methodArgs;
        $this->interceptors = $interceptors;
        if(is_array($parameters)){
            $this->parameters_ = $parameters;
        }else{
            $this->parameters_ = array();
        }
    }
    function getTargetClass() {
    	return $this->targetClass;
    }
    function getParameter($name) {
        if(array_key_exists($name,$this->parameters_)){
        	return $this->parameters_[$name];
        }
        return null;
    }
    function getMethod() {
        return $this->method;
    }
    function getArguments(){
         return $this->methodArgs;
    }
    function getStaticPart(){
    }
    function getThis(){
        return $this->target;
    }
    function proceed(){
        if($this->interceptorIndex < count($this->interceptors)){
            return $this->interceptors[$this->interceptorIndex++]->invoke($this);
        }else{
            $method = $this->method->getName();
            return S2Container_MethodUtil::invoke($this->method,$this->target,$this->methodArgs);
        }
    }
}
?>
