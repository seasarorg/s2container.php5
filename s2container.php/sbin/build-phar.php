<?php
$ROOT_DIR   = dirname(dirname(__FILE__));
$PHAR_NAME  = 'S2Container';
$PHAR_ALIAS = 'seasar.s2container';
$VERSION    = '2.0.0RC2';
$PHAR_FILE  = dirname(dirname(__FILE__)) . "/$PHAR_NAME-$VERSION.phar";

if (file_exists($PHAR_FILE)) {
    unlink($PHAR_FILE);
}

$phar = new Phar($PHAR_FILE, 0, $PHAR_ALIAS);
//$phar->buildFromDirectory(dirname(dirname(__FILE__)) . '/classes', '/^[^\.])/i');

$path = $ROOT_DIR . DIRECTORY_SEPARATOR;
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
        !preg_match('/^S2Container/', $item) and
        !preg_match('/^Apache/', $item)) {
        continue;
    }

    if (preg_match('/(\.tgz|\.phar)/', $item)) {
        continue;
    }

    $phar->addFile($ite->getRealPath(), $item);
    print "addFile {$ite->getRealPath()} as $item" . PHP_EOL;
}

$stub = <<< EOSTUB
<?php
    Phar::mapPhar('$PHAR_ALIAS');
    define('S2CONTAINER_ROOT_DIR', 'phar://$PHAR_ALIAS');
    require_once("phar://$PHAR_ALIAS/S2Container.php");
    __HALT_COMPILER();
EOSTUB;
//print $stub;
$phar->setStub($stub);

//$phar = new Phar($PHAR_FILE);
//$phar->extractTo(dirname(__FILE__) . '/tmp');


