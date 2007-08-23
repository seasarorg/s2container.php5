<?php
class PHPUnitAllTestTask extends Task {

    private $incFileSets = array();
    private $filterChains = array();
    private $name;

    public function init(){}

    public function main(){
        $includefiles = array();
        foreach($this->incFileSets as $fileset){
            $includefiles[] = $this->getFileList($fileset);
        }

        if (!defined("PHPUnit_MAIN_METHOD")) {
            define("PHPUnit_MAIN_METHOD", "");
        }
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite = new PHPUnit_Framework_TestSuite($this->name);
        foreach($includefiles as $files){
            $c = count($files);
            for($i = 0; $i < $c; $i++){
                require_once($files[$i]['absolute']);
                $this->log('add : ' . $files[$i]['name']);
                $suite->addTest(new PHPUnit_Framework_TestSuite(new ReflectionClass($files[$i]['class'])));
            }
        }
        PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function createFileSet(){
        $fs = new FileSet();
        $this->incFileSets[] = $fs;
        return $fs;
    }

    public function createFilterChain(){
        $fc = new FilterChain($this->project);
        $this->filterChains[] = $fc;
        return $fc;
    }

    public function setName($name){
        $this->name = $name;
    }

    private function getFileList(FileSet $fileset){
        $ds = $fileset->getDirectoryScanner($this->project);
        $files = $ds->getIncludedFiles();
        foreach($files as &$file){
            $relativePath = $ds->getBaseDir() . DIRECTORY_SEPARATOR . $file;
            preg_match("/^([^\.]+)/", basename($file), $matches);
            $file = array(
                        "key" => $file,
                        "relative" => $relativePath, 
                        "absolute" => realpath($relativePath), 
                        "name"     => basename($file),
                        "class"    => $matches[1]
                    );
        }
        return $files;
    }

}
?>
