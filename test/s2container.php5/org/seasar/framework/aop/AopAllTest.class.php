<?php
class AopAllTest {

    public static function main(){
        if (TextReporter::inCli()) {  
            print "\n\n============================\n";
            exit (self::group()->run(new TextReporter()) ? 0 : 1);
        }
        self::group()->run(new HtmlReporter()); 	
    }
    
    public static function group() {
        $group = new GroupTest('');

        $group->addTestCase(new PointcutImplTests());
        $group->addTestCase(new InterceptorsTests());
        $group->addTestCase(new UuCallAopProxyExtensionTests());
        $group->addTestCase(new DelegateInterceptorTests());
        $group->addTestCase(new AbstractInterceptorTests());
        $group->addTestCase(new TraceInterceptorTests());

        return $group;    	
    }
}
?>