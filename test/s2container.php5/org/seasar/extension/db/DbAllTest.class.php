<?php
class DbAllTest {

    public static function main(){
        if (TextReporter::inCli()) {  
            print "\n\n============================\n";
            exit (self::group()->run(new TextReporter()) ? 0 : 1);
        }
        self::group()->run(new HtmlReporter()); 	
    }
    
    public static function group() {
        $group = new GroupTest('');

        //$group->addTestCase(new MySQLConnectTests());
        $group->addTestCase(new PearDBTests());
        $group->addTestCase(new ADOdbTests());
        $group->addTestCase(new MySQLTests());
        //$group->addTestCase(new PostgresTests());
        $group->addTestCase(new SqlCommandImplTests());

        return $group;    	
    }
}
?>