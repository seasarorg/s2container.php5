<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
 * Copyright 2004-2005 Project Guarana Development Team
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * @package root
 */
/**
 * @file PhingSetup.php
 * @brief Phing Setupper
 * @author <a href="mailto:isitoya@wakhok.ac.jp>ISITOYA Kentaro</a>
 * @version $Id$
 *
 * Tool for setup phing
 */

/**
 * Tool for set up phing
 * @class PhingSetup
 */
class PhingSetup{
    const COMMON_FILE = "./common.properties";
    const PROJECT_FILE = "./project.xml";

    /** repositories*/
    private $repositories = array();

    /** local repository */
    private $localRepository = "";

    /**
     * main entry point function
     * @param args array arguments
     */
    public static function main($args){
        $setupTool = new PhingSetup();
        $setupTool->execute($args);
    }

    /**
     * call module
     * @param args array arguments
     * @return result array of result
     */
    public function execute($args){
        if(ini_get("output_buffering") != 0 ||
           ini_get("output_handler") != null){
            $this->showMessage("!!WARNING!! \n There are php.ini settings of output_buffering or output_handler is setted. So phing will not show you a message while execution is done. But the setup process is continuing in background. Please be patient while set up process have done.\n If you want to turning off output_buffering, please check out php.ini-dist for default setting. \n\n");
        }
        $this->showMessage("Starting setup phing environment.");
        $this->parseArguments($args);

        $file = file_get_contents(self::COMMON_FILE);
        $this->localRepository = $this->getProperty("setup.repository", $file);
        $this->createDirectory($this->localRepository);

        $xml = simplexml_load_file(self::PROJECT_FILE);
        $repositories = $xml->repositories->repository;
        foreach($repositories as $repository){
            $this->repositories[] = (string)$repository->url;
            $this->showMessage("  Using $repository->url");
        }

        $phing = $xml->xpath("//dependency[name='phing']");
        $phing = $phing[0];
        $soya = $xml->xpath("//dependency[name='soya_tasks']");
        $soya = $soya[0];

        $this->download($phing);
        $this->download($soya);

        $this->callPhingMaven();

        $this->deleteDirectory($this->localRepository);
        $this->showMessage("setup completed!");
    }

    public function download($settings){
        $this->showMessage("Installation process started");
        $name = (string)$settings->name;
        $path = (string)$settings->deployPath;
        $version = (string)$settings->version;
        $localModification = (string)$settings->localModification;
        $type = (string)$settings->type;
        $filename = "$name-$version" .
          (empty($localModification) ? "" : "-$localModification");
        $archiveName = "$filename.tgz";
        $confName = "$filename.xml";

        $pearName = str_replace("_", "/", $name);
        $pearName = $pearName . ".php";
        $pearName = PEAR_INSTALL_DIR . "/" . $pearName;
        $pearName = $this->normalizePath($pearName);
        if(is_readable($pearName)){
            $this->showMessage("$name is already installed as PEAR. please check version, the version that we have is $version\n");
            return;
        }
        $remoteRepository = null;
        foreach($this->repositories as $repository){
            $repository = "$repository/$name/archives/$type/";
            $file = @file_get_contents("$repository/$confName");
            if(empty($file) == false){
                $remoteRepository = $repository;
                break;
            }
        }

        $archiveURI = "$remoteRepository/$archiveName";

        $this->showMessage("Download $name from $archiveURI");
        $localFileName = $this->normalizePath($this->localRepository . "/$filename");
        $archive = file_get_contents($archiveURI);
        file_put_contents($localFileName, $archive);
        $this->showMessage("  $name downloaded as $localFileName");

        $conf = file_get_contents("$remoteRepository/$confName");
        $deployConfPath = $this->normalizePath($path . "/$confName");

        $this->showMessage("  Going to install it.");
        $this->deleteDirectory($path);
        $this->createDirectory(dirname($path));

        exec("tar xfz " . $localFileName . " -C " . dirname($path));

        if($name == "phing" || $type == "pear"){
            $this->renameFile(dirname($path) . "/$filename", $path);
        }
        file_put_contents($deployConfPath, $conf);
        $this->showMessage("  $name is installed.");
    }

    /**
     * call phing maven
     */
    public function callPhingMaven(){
        putenv("PHING_PHP_OPTION=-d output_buffering=off -d output_handler= -d implicit_flush=True -d max_execution_time=");
        $this->showMessage("Now we are completed phing setup, calling phingMaven.");
        $this->showMessage("On WINDOWS, there will be no messages while phing executing. Wait for 5 minuite.");
        $this->copyFile("./build-dist.properties", "./build.properties");
        $file = file_get_contents($this->normalizePath("./build.properties"));

        $localRepos = $this->getProperty("phingMaven.local.repository", $file);

        $this->showMessage("Please input your phing maven local repository[$localRepos] >");
        $line = trim(fgets(STDIN));
        if($line != ""){
            $localRepos = $line;
        }

        echo"Going to use $localRepos as local repository of phing maven.";
        flush();
        ob_flush();
        
        $command = $this->normalizePath("./phing -f etc/setup.xml") . " -DphingMaven.local.repository=$localRepos";
        passthru($command);

        $file =
          $this->putProperty("phingMaven.local.repository", $localRepos, $file);
        file_put_contents($this->normalizePath("./build.properties"), $file);
        putenv("PHING_PHP_OPTION=");
        return;
    }

    /**
     * copy file
     * @param $src string src
     * @param $dest string dest
     */
    public function copyFile($src, $dest){
        $src = $this->normalizePath($src);
        $dest = $this->normalizePath($dest);
        if(is_readable($dest)){
            return;
        }
        copy($src, $dest);
    }

    /**
     * get property from file
     * @param string $target target property
     * @param string $file property file
     */
    public function getProperty($target, $file){
        $pattern = '/' . $target . '\s*=\s*(.+)/';
        if(preg_match($pattern, $file, $regs) == false){
            $this->showMessage("Variable $target is not declared in properties file.");
        }
        return trim($regs[1]);
    }

    /**
     * get property from file
     * @param $target string target property
     * @param $value string value
     * @param $file string property file
     * @return string replaced string
     */
    public function putProperty($target, $value, $file){
        $pattern = '/' . $target . '\s*=\s*.+/';
        $file = preg_replace($pattern, "$target=$value", $file);
        if($file == false){
            $this->showMessage("Variable $target is not declared in properties file.");
        }
        return $file;
    }

    /**
     * delete directory
     * @param $path string target path
     */
    public function deleteDirectory($path){
        $path = $this->normalizePath($path);
        if(is_dir($path)){
            $this->rmdirr($path);
        }
    }

    /**
     * create directory
     * @param $path string target path
     */
    public function createDirectory($path){
        $path = $this->normalizePath($path);
        if(is_readable($path) == false){
            mkdir($path, octdec('0755'), true);
        }
    }

    /**
     * rename file
     * @param $src string source path
     * @param $dest string destnation
     */
    public function renameFile($src, $dest){
        $src = $this->normalizePath($src);
        $dest = $this->normalizePath($dest);
        rename($src, $dest);
    }


    /**
     * normalize path
     * @param $path string target path
     * @return string path
     */
    public function normalizePath($path){
        $sep = (DIRECTORY_SEPARATOR == "/") ? '\\' : '/';
        $path = str_replace($sep, DIRECTORY_SEPARATOR, $path);
        return $path;
    }

    /**
     * show message
     * @param $msg string message
     */
    public function showMessage($msg){
        echo $msg . "\n";
        flush();
        ob_flush();
    }

    /**
     * 25.07.2005
     * Author: Anton Makarenko
     * makarenkoa at ukrpost dot net
     * webmaster at eufimb dot edu dot ua
     * Original idea:
     * http://ua2.php.net/manual/en/function.rmdir.php
     * 28-Apr-2005 07:35
     * development at lab-9 dot com
     */
    public function rmdirr($target,$verbose=false){
        $exceptions=array('.','..');
        $target = $this->normalizePath($target);
        if (!$sourcedir=@opendir($target)){
            if ($verbose){
                echo '<strong>Couldn&#146;t open '.$target."</strong><br />";
            }
            return false;
        }

        while(false!==($sibling=readdir($sourcedir))){
            if(!in_array($sibling,$exceptions)){
                $object=str_replace('//','/',$target.'/'.$sibling);
                $object = $this->normalizePath($object);
                if($verbose){
                    echo 'Processing: <strong>'.$object."</strong><br />";
                }
                if(is_dir($object)){
                    $this->rmdirr($object, $verbose);
                }
                if(is_file($object)){
                    $result=@unlink($object);
                    if ($verbose&&$result){
                        $this->showMessage("File has been removed<br />");
                    }
                    if ($verbose&&(!$result)){
                        $this->showMessage("<strong>Couldn&#146;t remove file</strong>");
                    }
                }
            }
        }
        closedir($sourcedir);
        if($result=@rmdir($target)){
            if ($verbose){
                $this->showMessage("Target directory has been removed<br />");
            }
            return true;
        }
        if ($verbose){
            $this->showMessage("<strong>Couldn&#146;t remove target directory</strong>");
        }
        return false;
    }

    /**
     * parse argument
     * @param args array arguments
     */
    private function parseArguments($args){
        reset($args);
        while($arg = current($args)){
            switch($arg) {
              case "?" :
              case "-h" :
              case "-?" :
              case "--help" :
                $this->printUsage();
            }
            next($args);
        }
    }

    /**
     * print tool usage
     */
    public function printUsage(){
        echo <<<EOD
          Setup phing and psd. and run maven.
            Requires PHP version 5.0.4 or higher.

            Usage:
        php -f setup.php -- [Options]

          ?|-h|-?|--help
            Show this help.

EOD;
        exit();
    }
}

// call main
$args = $_SERVER["argv"];
array_shift($args);
PhingSetup::main($args);

?>
