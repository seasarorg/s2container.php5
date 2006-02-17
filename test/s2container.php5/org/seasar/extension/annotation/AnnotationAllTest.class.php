<?php
class AnnotationAllTest {

    public static function main(){
        if (TextReporter::inCli()) {  
            print "\n\n============================\n";
            exit (self::group()->run(new TextReporter()) ? 0 : 1);
        }
        self::group()->run(new HtmlReporter()); 	
    }
    
    public static function group() {
        $group = new GroupTest('');

        $group->addTestCase(new AnnotationFactoryTests());
        $group->addTestCase(new CommentAnnotationReaderTests());
        $group->addTestCase(new AnnotationContainerTests());
        $group->addTestCase(new AnnotationsTests());
        $group->addTestCase(new ClassPatternTests());
        $group->addTestCase(new ClassTraversalTests());
        $group->addTestCase(new AbstractAutoNamingTests());
        $group->addTestCase(new DefaultAutoNamingTests());
        $group->addTestCase(new FileSystemComponentAutoRegisterTests());
        $group->addTestCase(new AbstractComponentAutoRegisterTests());
        $group->addTestCase(new DefaultAnnotationHandlerTests());
        $group->addTestCase(new AspectAutoRegisterTests());
        $group->addTestCase(new InterfaceAspectAutoRegisterTests());
        
        return $group;    	
    }
}
?>