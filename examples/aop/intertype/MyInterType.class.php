<?php

class MyInterType extends S2Container_AbstractInterType {

    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        parent::introduce($targetClass, $enhancedClass);

        $this->createContents();
        $this->createProperty();
        $this->createMethod1();
        $this->createMethod2();
    }

    private function createContents(){
        $this->addConstant('HOGE', 1234);
        $this->addConstant('FOO', "'1234'");
        $this->addConstant('BAR', "'" . __CLASS__  . "'");
        $this->addConstant('MyCLASS', "__CLASS__");
    }

    private function createProperty(){
        $this->addProperty(array(self::PUBLIC_), 'public_');
        $this->addProperty(array(self::PROTECTED_), 'protected_');
        $this->addProperty(array(self::PRIVATE_), 'private_');
        $this->addProperty(array(self::PROTECTED_, self::STATIC_), 'static_');
    }

    private function createMethod1(){
        $src = array();
        $src[] = '(){';
        $src[] = '    echo "CALL helloMethod" . PHP_EOL;';
        $src[] = '    echo "self::HOGE = ", self::HOGE, PHP_EOL;';
        $src[] = '    echo "self::FOO = ", self::FOO, PHP_EOL;';
        $src[] = '    echo "self::BAR = ", self::BAR, PHP_EOL;';
        $src[] = '    echo "self::MyCLASS = ", self::MyCLASS, PHP_EOL;';
        $src[] = '    echo "\$this->public_ = ", $this->public_, PHP_EOL;';
        $src[] = '    echo "\$this->protected_ = ", $this->protected_, PHP_EOL;';
        $src[] = '    echo "\$this->private_ = ", $this->private_, PHP_EOL;';
        $src[] = '    echo "self::\$static_ = ", self::$static_, PHP_EOL;';
        $src[] = '}';
        $this->addMethod(array(self::PUBLIC_), 'hello', implode(PHP_EOL, $src));
    }

    private function createMethod2(){
        $src = array();
        $src[] = '($protectedValue, $privateValue, $staticValue){';
        $src[] = '    echo "CALL setMethod" . PHP_EOL;';
        $src[] = '    var_dump(func_get_args());';
        $src[] = '    $this->protected_ = $protectedValue;';
        $src[] = '    $this->private_ = $privateValue;';
        $src[] = '    self::$static_ = $staticValue;';
        $src[] = '    $this->hello();';
        $src[] = '}';
        $this->addMethod(array(self::PUBLIC_), 'set', implode(PHP_EOL, $src));
    }
    
}

?>
