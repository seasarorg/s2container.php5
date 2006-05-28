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
            
        return $group;    	
    }
}
?>