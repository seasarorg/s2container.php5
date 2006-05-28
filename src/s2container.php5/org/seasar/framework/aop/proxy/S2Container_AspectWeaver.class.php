<?php

/**
 * @author nowel
 * @version fixme...orz
 */
class S2Container_EnhancedClassGenerator {

    // {{{ property
    private $targetClass = null;
    private $source = array();
    private $enhancedClassName = '';
    // }}}
    
    public function __construct(ReflectionClass $targetClass, $enhancedClassName){
        $this->targetClass = $targetClass;
        $this->enhancedClassName = $enhancedClassName;

        $this->source = S2Container_ClassUtil::getClassSource($this->targetClass);
        $this->setupClass();
        $this->setupInterface();
        $this->setupConstractor();
    }

    protected function setupClass(){
        $class = $this->enhancedClassName;
        /*
        if(!($this->targetClass->isInterface() && 
                $this->targetClass->isFinal())){
            $class .= ' extends ' . $this->targetClass->getName();
        }
        */
        $this->source[0] = str_replace($this->targetClass->getName(),
                                       $class, $this->source[0]);
        $c = count($this->source) - 1;
        $this->source[$c] = '';
    }

    protected function setupInterface(){
        if($this->targetClass->isInterface()){
        }
    }

    protected function setupConstractor(){
    }

    public function addMethod($modify, $name, $src){
        // fixme
        $method = 'public function ' . $name . $src;
        $this->source[] = $method;
    }

    public function addProperty(){
    }

    public function addConstant(){
    }

    public function applyInterType(S2Container_InterType $interType) {
        $interType->introduce($this->targetClass, $this->enhancedClassName);
    }
    
    public function getConcreteClass(){
        $this->source[] = '}';
        return implode(PHP_EOL, $this->source);
    }
}

/**
 * @author nowel
 */
class S2Container_AspectWeaver {

    // {{{ const
    const PREFIX_ENHANCED_CLASS = '$$';
    const SUFFIX_ENHANCED_CLASS = 'EnhancedByS2AOP';
    const SUFFIX_METHOD_INVOCATION_CLASS = 'MethodInvocation';
    const SUFFIX_INVOKE_SUPER_METHOD = 'invokeSuperMethod';
    // }}}

    // {{{ static properties
    protected static $enhancedClassNames = array();
    // }}}

    // {{{ properties
    protected $targetClass;
    protected $parameters;
    protected $enhancedClassName;
    protected $enhancedClassGenerator;
    protected $methodInvocationClassList = array();
    protected $enhancedClass = null;
    // }}}

    public function __construct(ReflectionClass $targetClass, $parameters) {
        $this->targetClass = $targetClass;
        $this->parameters = $parameters;
        $this->enhancedClassName = $this->getEnhancedClassName();
        $this->enhancedClassGenerator = new S2Container_EnhancedClassGenerator($this->targetClass, $this->enhancedClassName);
    }

    public function setInterceptors(ReflectionMethod $method,
                                    array $interceptors) {
        /*
        $methodInvocationClassName = $this->getMethodInvocationClassName($method);
        $methodInvocationGenerator = new S2Container_MethodInvocationClassGenerator(
                $classPool, $methodInvocationClassName, $this->enhancedClassName);

        $invokeSuperMethodName = $this->createInvokeSuperMethod($method);
        $methodInvocationGenerator->createProceedMethod($method, $invokeSuperMethodName);
        $enhancedClassGenerator->createTargetMethod($method, $methodInvocationClassName);

        $methodInvocationClass = $methodInvocationGenerator->toClass($this->targetClass->getName());
        $this->setStaticField($methodInvocationClass, "method", $method);
        $this->setStaticField($methodInvocationClass, "interceptors", $interceptors);
        $this->setStaticField($methodInvocationClass, "parameters", $parameters);
        array_push($this->methodInvocationClassList, $methodInvocationClass);
        */
    }

    public function setInterTypes(array $interTypes = null) {
        if ($interTypes === null) {
            return;
        }

        $c = count($interTypes);
        for ($i = 0; $i < $c; ++$i) {
            $it = $interTypes[$i]->getInterType();
            $this->enhancedClassGenerator->applyInterType(new $it($this->enhancedClassGenerator));
        }
    }

    public function generateClass() {
        if ($this->enhancedClass === null) {
            $this->enhancedClass = $this->enhancedClassGenerator->getConcreteClass();

            $c = count($this->methodInvocationClassList);
            for ($i = 0; $i < $c; ++$i) {
                $methodInvocationClass = array_pop($this->methodInvocationClassList);
                $this->setStaticField($this->methodInvocationClass, 'targetClass', $this->targetClass);
            }
        }
        eval($this->enhancedClass);
        return new $this->enhancedClassName($this->parameters);
    }

    public function getEnhancedClassName() {
        $buf = '';
        $targetClassName = $this->targetClass->getName();
        $buf .= $targetClassName . self::SUFFIX_ENHANCED_CLASS;

        $length = strlen($buf);
        for ($i = 0; $i < $length;  ++$i) {
            $buf .= '_' . $i;
        }

        $name = $buf;
        self::$enhancedClassNames[] = $name;
        return $name;
    }

    public function getMethodInvocationClassName(ReflectionMethod $method) {
        /*
        return $this->enhancedClassName . self::SUFFIX_METHOD_INVOCATION_CLASS .
            $method->getName() . count($this->methodInvocationClassList);
        */
    }

    public function createInvokeSuperMethod(ReflectionMethod $method) {
        /*
        $invokeSuperMethodName = $method->getName() . self::SUFFIX_INVOKE_SUPER_METHOD;
        if (!S2Container_MethodUtil::isAbstract($method)) {
            $this->enhancedClassGenerator->createInvokeSuperMethod($method, $invokeSuperMethodName);
        }
        return $invokeSuperMethodName;
        */
    }

    public function setStaticField(ReflectionClass $clazz, $name, $value) {
    }
}

?>
