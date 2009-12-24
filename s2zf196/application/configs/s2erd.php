<?php
/**
 * MySQL Workbench用のERDファイルParserの登録
 */
s2component('seasar\erd\parser\MwbParser')->setConstructClosure(function() {
    $parser = new \seasar\erd\parser\MwbParser;
    $parser->setSchemaName('s2');
    $parser->setErdFile(dirname(APPLICATION_PATH) . '/var/db/project.mwb');
    // MySQL Workbenchのデータファイルはzip圧縮されています。PHPのzip拡張モジュールが無効な場合は、
    // あらかじめ手動でunzipする必要があります。unzipすると、document.mwb.xmlファイルが展開されます。
    //$parser->setErdFile(dirname(APPLICATION_PATH) . '/var/db/document.mwb.xml');
    return $parser;
});

/**
 * A5ER用のERDファイルParserの登録
s2component('seasar\erd\parser\A5Parser')->setConstructClosure(function() {
    $parser = new \seasar\erd\parser\A5Parser;
    $parser->setErdFile(dirname(APPLICATION_PATH) . '/var/db/project.a5er');
    return $parser;
});
 */

/**
 * ER Master用のERDファイルParserの登録
s2component('seasar\erd\parser\ErmParser')->setConstructClosure(function() {
    $parser = new \seasar\erd\parser\ErmParser;
    $parser->setErdFile(dirname(APPLICATION_PATH) . '/var/db/project.erm');
    return $parser;
});
 */


/**
 * Zend_Db_Tableクラスソース生成用のGeneratorの登録
 */
s2component('seasar\erd\generator\zend\db\MySQLGenerator')->setConstructClosure(function() {
    $generator = new \seasar\erd\generator\zend\db\MySQLGenerator;
    $generator->setSaveDir(APPLICATION_PATH . '/models/DbTable');
    $generator->setModelSuperClassTplFile(dirname(APPLICATION_PATH) . '/var/db/tpl/model_abstract.tpl');
    $generator->setModelClassTplFile(dirname(APPLICATION_PATH) . '/var/db/tpl/model.tpl');
    return $generator;
});



