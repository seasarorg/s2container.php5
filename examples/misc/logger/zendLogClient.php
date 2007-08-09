<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php'); 
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');
require_once('Zend/Log/Filter/Priority.php');

define('S2CONTAINER_PHP5_LOG_LEVEL', Zend_Log::DEBUG);
//define('S2CONTAINER_PHP5_DEBUG_EVAL',true);

/*
EMERG   = 0;  // 緊急事態 (Emergency): システムが使用不可能です
ALERT   = 1;  // 警報 (Alert): 至急対応が必要です
CRIT    = 2;  // 危機 (Critical): 危機的な状況です
ERR     = 3;  // エラー (Error): エラーが発生しました
WARN    = 4;  // 警告 (Warning): 警告が発生しました
NOTICE  = 5;  // 注意 (Notice): 通常動作ですが、注意すべき状況です
INFO    = 6;  // 情報 (Informational): 情報メッセージ
DEBUG   = 7;  // デバッグ (Debug): デバッグメッセージ
*/

S2Container_S2Logger::$LOGGER_FACTORY = 'S2Container_ZendLoggerFactory';
S2Container_S2Logger::setLoggerFactory(null);
$logger = S2Container_S2Logger::getLogger('s2base_zf');
$writer = new Zend_Log_Writer_Stream(dirname(__FILE__) . '/zendLog.log');
$logger->addWriter($writer);
$logger->addFilter(new Zend_Log_Filter_Priority(S2CONTAINER_PHP5_LOG_LEVEL));

$logger->debug('test debug');
$logger->info ('test info ');
$logger->notice('test notice ');
$logger->warn ('test warn ');
$logger->err('test err ');
$logger->crit('test fatal');
$logger->alert('test alert ');
$logger->emerg('test emerg ');
?>
