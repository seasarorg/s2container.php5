<?xml version="1.0" encoding="UTF-8"?>
<package packagerversion="1.5.0" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0     http://pear.php.net/dtd/tasks-1.0.xsd     http://pear.php.net/dtd/package-2.0     http://pear.php.net/dtd/package-2.0.xsd">
 <name>S2Erd</name>
 <channel>pear.php.net</channel>
 <summary>S2Erd</summary>
 <description>S2Erd</description>
 <lead>
  <name>klove</name>
  <user>klove</user>
  <email>s2container-php5@ml.seasar.org</email>
  <active>yes</active>
 </lead>
 <date><?php echo date('Y-m-d');?></date>
 <time><?php echo date('H:i:s');?></time>
 <version>
  <release>0.3.0</release>
  <api>0.3.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.apache.org/licenses/LICENSE-2.0">The Apache License, Version 2.0</license>
 <notes>Release 0.3.0</notes>
 <contents>
  <dir name="/">
<?php
    define('ROOT_DIR', dirname(dirname(__FILE__)));
    $ret = array();
    $path = ROOT_DIR . DIRECTORY_SEPARATOR;
    $r_ite = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($r_ite as $ite) {
        if (!$ite->isFile() or 
            preg_match('/^\./', $ite->getFileName()) or
            preg_match('/\.svn/i', $ite->getRealPath()) ) {
            continue;
        }
        $item = str_replace($path, '', $ite->getRealPath());
        $item = str_replace(DIRECTORY_SEPARATOR, '/', $item);
        if (!preg_match('/^classes/', $item) and
            !preg_match('/^S2Erd/', $item) and
            !preg_match('/^Apache/', $item)) {
            continue;
        }

        if (preg_match('/(\.tgz|\.phar)/', $item)) {
            continue;
        }

        print "    <file role=\"php\" name=\"S2Erd/$item\" />" . PHP_EOL;
    }
    print "    <file role=\"php\" name=\"S2Erd.php\" />" . PHP_EOL;
?>
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>5.3.0</min>
   </php>
   <pearinstaller>
    <min>1.5.0</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
 <changelog />
</package>
