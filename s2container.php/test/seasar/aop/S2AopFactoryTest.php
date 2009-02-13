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
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar\aop;
class S2AopFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testA() {
        $targetRef = new \ReflectionClass('\seasar\aop\A_S2AopFactory');
        $interceptor = new \seasar\aop\interceptor\TraceInterceptor;
        $pointcut = new Pointcut($targetRef);
        $aspects = array(new Aspect($interceptor, $pointcut));
        $targetOjb = S2AopFactory::create($targetRef, $aspects, array());
    }

    public function testB() {
        $targetRef = new \ReflectionClass('\seasar\aop\B_S2AopFactory');
        $interceptor = new \seasar\aop\interceptor\TraceInterceptor;
        $pointcut = new Pointcut('/^service$/');
        $aspects = array(new Aspect($interceptor, $pointcut));
        $targetObj = S2AopFactory::create($targetRef, $aspects, array());
        $targetObj->service();
    }

    public function testInvokeAbstractMethod() {
        $targetRef = new \ReflectionClass('\seasar\aop\F_S2AopFactory');
        $interceptor = new \seasar\aop\interceptor\TraceInterceptor;
        $pointcut = new Pointcut($targetRef);
        $aspects = array(new Aspect($interceptor, $pointcut));
        $targetObj = S2AopFactory::create($targetRef, $aspects, array());
        try{
            $targetObj->service();
            $this->fail();
        } catch(\seasar\aop\exception\AbstractMethodInvocationRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
            $this->fail();
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_S2AopFactory {}

class B_S2AopFactory {
    public function service() {
        print __METHOD__ . ' called.' . PHP_EOL;
    }
}

class C_S2AopFactory {
    public $name;
}

class D_S2AopFactory {
    public $name = 'S2Binding:hoge[]';

    private $year;
    public function setYear(array $year) {
        $this->year = $year;
    }
    public function getYear() {
        return $this->year;
    }
}

class E_S2AopFactory {}

interface F_S2AopFactory {
    public function service();
}

class G_S2AopFactory {
    private $clazz_EnhancedByS2AOP = null;
}

class H_S2AopFactory {
    public function service_EnhancedByS2AOP(){}
    public function service(){}
}
