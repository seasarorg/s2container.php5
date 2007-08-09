<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php'); 
require_once('Zend/Log.php');
require_once('Zend/Log/Writer/Stream.php');
require_once('Zend/Log/Filter/Priority.php');

define('S2CONTAINER_PHP5_LOG_LEVEL', Zend_Log::DEBUG);
//define('S2CONTAINER_PHP5_DEBUG_EVAL',true);

/*
EMERG   = 0;  // �ً}���� (Emergency): �V�X�e�����g�p�s�\�ł�
ALERT   = 1;  // �x�� (Alert): ���}�Ή����K�v�ł�
CRIT    = 2;  // ��@ (Critical): ��@�I�ȏ󋵂ł�
ERR     = 3;  // �G���[ (Error): �G���[���������܂���
WARN    = 4;  // �x�� (Warning): �x�����������܂���
NOTICE  = 5;  // ���� (Notice): �ʏ퓮��ł����A���ӂ��ׂ��󋵂ł�
INFO    = 6;  // ��� (Informational): ��񃁃b�Z�[�W
DEBUG   = 7;  // �f�o�b�O (Debug): �f�o�b�O���b�Z�[�W
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
